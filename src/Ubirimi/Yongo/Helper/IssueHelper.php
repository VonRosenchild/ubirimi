<?php

namespace Ubirimi\Yongo\Helper;

class IssueHelper
{
    public static function renderInput($id, $required = false)
    {
        return '<input ' . self::convertRequired($required) .
            '" id="field_type_' . $id .
            '" class="inputTextLarge mousetrap" type="text" value="" name="' . $id . '" />';
    }

    public static function renderUserSelect($id, $users, $selectedId, $disabled = false, $required = false, $hasEmpty = false)
    {
        $htmmlSelect = '<select '
            . self::convertDisabled($disabled) . ' '
            . self::convertRequired($required) . ' '
            . 'id="field_type_' . $id .'"' . ' '
            . 'name="' . $id . '"' . ' '
            . 'class="select2Input mousetrap">';

        $htmlOption = '';

        if ($hasEmpty) {
            $htmlOption .= '<option value="-1">No one</option>';
        }

        foreach ($users as $user) {
            $htmlSelected = '';
            if ($selectedId == $user['user_id']) {
                $htmlSelected = 'selected="selected"';
            }

            $htmlOption .= '<option ' . $htmlSelected . ' value="' . $user['user_id'] . '">' . $user['first_name'] . ' ' . $user['last_name'] . '</option>';
        }

        return $htmmlSelect . $htmlOption . '</select>';
    }

    public static function renderSelect($id, $values, $required = false, $mouseTrap = true)
    {
        if ($mouseTrap) {
            $classHtml = 'class="select2Input mousetrap"';
        } else {
            $classHtml = 'class="select2Input"';
        }

        $htmmlSelect = '<select '
            . self::convertRequired($required)
            . 'id="field_type_' . $id . '"' . ' '
            . 'name="' . $id . '"' . ' '
            . $classHtml
            . '>';

        $htmlOption = '';

        foreach ($values as $value) {
            $htmlOption .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }

        return $htmmlSelect . $htmlOption . '</select>';
    }

    public static function renderTextarea($id, $rows = 2, $required = false)
    {
        return '<textarea '
            . self::convertRequired($required)
            . 'id="field_type_' . $id . '"' . ' '
            . 'rows="' . $rows . '"' . ' '
            . 'class="inputTextAreaLarge mousetrap"' . ' '
            . 'name="' . $id . '"></textarea>';
    }

    private static function convertRequired($required)
    {
        return $required === true ? 'required="1"' : 'required="0"';
    }

    private static function convertDisabled($disabled)
    {
        return $disabled === true ? 'disabled="disabled"' : '';
    }
}
