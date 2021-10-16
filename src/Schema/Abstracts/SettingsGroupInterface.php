<?php

namespace Atwinta\Voyager\Schema\Abstracts;


/**
 * Interface SettingGroupInterface
 * @package Atwinta\Voyager\Schema\Abstracts
 */
interface SettingsGroupInterface
{
    /**
     * @return string
     */
    public static function getSettingGroupName(): string;

    /**
     * Returned array should look like this:
     *  [
     *      [
     *          'key' => "page_title",
     *          'display_name' => "Заголовок",
     *          'value' => '',
     *          'details' => '',
     *          'type' => FieldType::TEXT,
     *      ],
     *      ...
     *  ]
     *
     * @return array
     */
    public static function getSettingGroupFields(): array;
}

