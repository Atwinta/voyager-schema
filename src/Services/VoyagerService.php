<?php

namespace Atwinta\Voyager\Services;


use Atwinta\Voyager\Domain\Enum\FieldType;
use Atwinta\Voyager\Schema\Abstracts\DataTypeInterface;
use Atwinta\Voyager\Services\Abstracts\VoyagerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

/**
 * Class VoyagerService
 * @package Atwinta\Voyager\Services
 */
class VoyagerService implements VoyagerInterface
{
    /** @var array */
    protected $menu;

    /** @var array */
    protected $schema;

    /** @var string|null */
    protected $defaultController = null;

    protected $defaultPolicy = null;

    /**
     * VoyagerService constructor.
     * @param array $schema
     * @param array $menu
     */
    public function __construct(
        array $config
    )
    {
        $this->defaultController = $config["default-controller"] ?? "";
        $this->defaultPolicy = $config["default-policy"] ?? "";
        $this->menu = $config["menu"];
        $this->schema = $config["schemas"];
    }

    /**
     * @inheritdoc
     */
    public function schemaGenerate()
    {
        foreach ($this->schema as $item) {
            $this->make($item);
        }
    }

    /**
     * @inheritdoc
     */
    public function menuGenerate()
    {
        /** @var Menu $menu */
        $menu = Menu::query()->with(["items"])->where('name', 'admin')->first();
        if ($menu) {
            $ids = $menu->items->pluck("id");

            $created = $this->recursiveInsert(
                $this->menu,
                $menu->id
            );

            $ids = $ids->diff($created);
            MenuItem::whereIn("id", $ids)->delete();
        }
    }

    // Menu

    private function recursiveInsert(array $items, int $menuId, ?int $parentId = null, int &$order = 0)
    {
        $ids = [];
        foreach ($items as $index => $item) {
            $children = $item["children"] ?? [];
            $order++;
            if (isset($item["custom"]) && $item["custom"]) {
                if (isset($item["locale"])) {
                    $item["title"] = __($item["locale"]);
                }
                unset($item["locale"], $item["custom"], $item["children"]);
                $current = MenuItem::query()->updateOrCreate([
                    "menu_id" => $menuId,
                    "order" => $order
                ], array_merge([
                    "parent_id" => $parentId,
                    "target" => "_self",
                    "url" => ""
                ], $item));
            } else {
                $item = $this->byModel($item["class"]);
                $item["target"] = "_self";
                unset($item["children"], $item["custom"]);

                $current = MenuItem::query()->updateOrCreate([
                    "menu_id" => $menuId,
                    "order" => $order
                ], array_merge([
                    "parent_id" => $parentId,
                    "url" => "",
                ], $item));
            }
            $ids[] = $current->id;
            if (count($children)) {
                $ids = array_merge($ids, $this->recursiveInsert($children, $menuId, $current->id, $order));
            }
        }
        return $ids;
    }

    private function byModel(string $class)
    {
        /** @var DataTypeInterface $object */
        $object = new $class;

        $data = $object->getDataTypeArray();
        $slug = $data["slug"] ?? $this->generateSlug($class);

        $title = $data["custom_menu_title"] ?? $data["display_name_plural"];
        $icon = $data["custom_menu_icon"] ?? "voyager-data";

        return [
            "route" => "voyager.{$slug}.index",
            "url" => $data["custom_menu_url"] ?? "",
            "title" => $title,
            "icon_class" => $icon,
        ];
    }

    // Schema
    private function make(string $model)
    {
        $dataType = $this->dataType($model);
        $this->dataSchema($model, $dataType->id);
    }

    private function dataType(string $model)
    {
        /** @var DataTypeInterface $object */
        $object = new $model;

        return DB::transaction(function () use ($model, $object) {
            $data = $object->getDataTypeArray();
            $roles = $data["roles"] ?? "*";
            if (!empty($data["custom_menu_icon"])) {
                $data["icon"] = $data["custom_menu_icon"];
            }
            unset($data["roles"], $data["custom_menu_title"], $data["custom_menu_icon"]);

            $dataType = DataType::query()->updateOrCreate([
                "name" => $object->table()->getTable()
            ], array_merge([
                    "slug" => $this->generateSlug($model),
                    "model_name" => $model,
                    "controller" => $this->defaultController,
                    "policy_name" => $this->defaultPolicy,
                    "server_side" => true,
                ], $data)
            );

            $permissions = ["browse", "read", "edit", "add", "delete"];

            if ($roles == "*") {
                $roles = Role::pluck("id");
            } else if (is_array($roles)) {
                $roles = Role::whereIn("name", $roles)->pluck("id");
            }

            foreach ($permissions as $permission) {
                $permission = Permission::query()->updateOrCreate([
                    "table_name" => $object->table()->getTable(),
                    "key" => $key = "{$permission}_{$object->table()->getTable()}"
                ]);

                if ($permission) {
                    foreach ($roles as $role) {
                        DB::table("permission_role")->updateOrInsert([
                            "permission_id" => $permission->id,
                            "role_id" => $role
                        ]);
                    }
                }
            }

            return $dataType;
        });
    }

    private function dataSchema(string $model, int $dataTypeId)
    {
        /** @var DataTypeInterface $object */
        $object = new $model;

        $rows = $object->getDataRowsArray();

        /** @var Model $model */
        $model = $object->table();

        $allFields = Schema::getColumnListing($model->getTable());

        $allFields = array_merge($allFields, array_keys($rows));

        DB::transaction(function () use ($rows, $allFields, $object, $model, $dataTypeId) {
            DataRow::query()->where("data_type_id", $dataTypeId)->delete();
            foreach ($allFields as $index => $field) {
                $type = $field == $model->getKeyName() && $model->getKeyType() == "int"
                    ? FieldType::NUMBER
                    : (
                        in_array($field, [Model::CREATED_AT, Model::UPDATED_AT]) ? FieldType::TIMESTAMP : FieldType::TEXT
                    );

                $required = $field == $model->getKeyName();
                $change = !in_array($field, [Model::UPDATED_AT, $model->getKeyName()]);

                $row = $rows[$field] ?? [];
                if ($row === false) {
                    continue;
                }
                if (isset($row["details"])) {
                    $row["details"] = json_encode($row["details"]);
                }

                DataRow::query()->updateOrInsert([
                    "data_type_id" => $dataTypeId,
                    "field" => $field,
                ], $array = array_merge([
                    "type" => $type,
                    "display_name" => $this->generateName($field),
                    "required" => $required,
                    "browse" => $change,
                    "read" => $change,
                    "edit" => $change,
                    "add" => $change,
                    "delete" => $change,
                    "details" => "{}",
                    "order" => $index + 1
                ], $row));
            }

            });

    }


    /**
     * @param string $field
     * @return mixed|string
     */
    private function generateName(string $field)
    {
        $field = str_replace("_", " ", ucwords($field, "_"));
        return $field;
    }

    /**
     * @param string $model
     * @return string
     */
    private function generateSlug(string $model)
    {
        $model = str_replace("App\\Models\\", "", $model);
        $items = explode("\\", $model);
        foreach ($items as &$item) {
            $item = Str::snake(Str::pluralStudly($item), "-");
        }

        return implode("-", $items);
    }
}
