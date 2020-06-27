<?php

namespace Atwinta\Voyager\Domain\Enum;


class FieldType
{
    const RELATIONSHIP = "relationship";

    const CHECKBOX = 'checkbox';

    const MULTIPLE_CHECKBOX = 'multiple_checkbox';

    const COLOR = 'color';

    const DATE = 'date';

    const FILE = 'file';

    const IMAGE = 'image';

    const MULTIPLE_IMAGES = 'multiple_images';

    const MEDIA_PICKER = 'media_picker';

    const NUMBER = 'number';

    const PASSWORD = 'password';

    const RADIO_BUTTON = 'radio_btn';

    const TEXT_EDITOR = 'rich_text_box';

    const CODE_EDITOR = 'code_editor';

    const MARKDOWN_EDITOR = 'markdown_editor';

    const SELECT_DROPDOWN = 'select_dropdown';

    const SELECT_MULTIPLE = 'select_multiple';

    const TEXT = 'text';

    const TEXT_AREA = 'text_area';

    const TIME = 'time';

    const TIMESTAMP = 'timestamp';

    const HIDDEN = 'hidden';

    const COORDINATES = 'coordinates';
}