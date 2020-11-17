<?php

namespace App\Schema\Tables;

use Atwinta\Voyager\Domain\Enum\FieldType;
use Atwinta\Voyager\Schema\BaseDataType;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\User;

/**
 * Class UserDataType
 * @package Atwinta\Voyager\Schema
 */
class UserDataType extends BaseDataType
{
    /**
     * @inheritdoc
     */
    protected function model(): string
    {
        return User::class;
    }

    /**
     * @inheritdoc
     */
    public function getDataTypeArray(): array
    {
        /**
         * @property string custom_menu_title - not required value
         * @property string custom_menu_icon - not required value
         * @property string custom_menu_url - not required value
         */
        return [
            "slug" => $this->table()->getTable(),
            "roles" => "*",
            "controller" => "TCG\Voyager\Http\Controllers\VoyagerUserController",
            "policy_name" => "TCG\Voyager\Policies\UserPolicy",
            "model_name" => $this->model(),
            "display_name_singular" => "Пользователь",
            "display_name_plural" => "Пользователи",
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDataRowsArray(): array
    {
        return [
            $this->table()->getKeyName() => [
                "type" => FieldType::NUMBER,
                "display_name" => "#"
            ],
            "name" => [
                "display_name" => "Имя",
            ],
            "email" => [
                "display_name" => "Почта",
            ],
            "password" => [
                "type" => FieldType::PASSWORD,
                "display_name" => "Пароль",
                "required" => true,
                "browse" => false,
                "read" => false,
                "edit" => true,
                "add" => true
            ],
            "remember_token" => [
                "display_name" => "Почта",
                "required" => true,
                "browse" => false,
                "read" => false,
                "edit" => false,
                "add" => false
            ],
            "avatar" => [
                "type" => FieldType::IMAGE,
                "display_name" => "Аватар",
                "required" => false,
            ],
            "user_belongsto_role_relationship" => [
                "type" => FieldType::RELATIONSHIP,
                "display_name" => "Роль",
                "required" => false,
                "delete" => false,
                "browse" => true,
                "details" => [
                    "model" => "TCG\\Voyager\\Models\\Role",
                    "table" => "roles",
                    "type" => "belongsTo",
                    "column" => "role_id",
                    "key" => "id",
                    "label" => "display_name",
                    "pivot_table" => "roles",
                    "pivot" => 0
                ]
            ],
            "user_belongstomany_role_relationship" => [
                "type" => FieldType::RELATIONSHIP,
                "display_name" => "Дополнительные роли",
                "required" => false,
                "delete" => false,
                "browse" => false,
                "details" => [
                    "model" => "TCG\\Voyager\\Models\\Role",
                    "table" => "roles",
                    "type" => "belongsToMany",
                    "column" => "id",
                    "key" => "id",
                    "label" => "display_name",
                    "pivot_table" => "user_roles",
                    "pivot" => "1",
                    "taggable" => "0"
                ]
            ],
            "role_id" => [
                "display_name" => "Роль"
            ],
            "settings" => [
                "type" => FieldType::HIDDEN,
                "display_name" => "Настройки",
                "browse" => false,
                "read" => false,
                "edit" => false,
                "add" => false,
                "delete" => false
            ],
            Model::CREATED_AT => [
                "display_name" => "Дата создания",
                "browse" => false,
                "read" => true,
                "edit" => false,
                "add" => false
            ],
        ];
    }
}
