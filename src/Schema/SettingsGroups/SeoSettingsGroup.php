<?php

namespace App\Schema\SettingsGroups;

use Atwinta\Voyager\Domain\Enum\FieldType;
use Atwinta\Voyager\Schema\Abstracts\SettingsGroupInterface;

/**
 * Class SeoSettingGroup
 * @package Atwinta\Voyager\Schema
 */
class SeoSettingsGroup implements SettingsGroupInterface
{
    /**
     * @inheritdoc
     */
    public static function getSettingGroupName(): string
    {
        return 'SEO';
    }

    /**
     * @inheritdoc
     */
    public static function getSettingGroupFields(): array
    {
        $pages = [
            [
                'slug' => 'main',
                'name' => 'Главная'
            ],
            [
                'slug' => 'contacts',
                'name' => 'Контакты'
            ],
        ];

        return self::makeSeoSettingsGroupFields($pages);
    }


    /**
     * @return array
     */
    private static function makeSeoSettingsGroupFields(array $pages): array
    {
        $fields = [];

        foreach ($pages as $page) {
            $fields[] = [
                'key' => "seo.{$page['slug']}_title",
                'display_name' => "[ {$page['name']} ]  META TITLE",
                'value' => '',
                'details' => '',
                'type' => FieldType::TEXT,
            ];

            $fields[] = [
                'key' => "seo.{$page['slug']}_description",
                'display_name' => "[ {$page['name']} ]  META DESCRIPTION ",
                'value' => '',
                'details' => '',
                'type' => FieldType::TEXT_AREA,
            ];
        }

        return $fields;
    }
}
