<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Yongo\Helper;

class IssueHelper
{
    public static function renderInput($id, $required = false, $style = null)
    {
        $inputStyle = '';
        if ($style) {
            $inputStyle = 'style="' . $style . '" ';
        }
        return '<input ' . self::convertRequired($required) .
            '" id="field_type_' . $id . '" ' .
            $inputStyle .
            'class="inputTextLarge mousetrap" type="text" value="" name="' . $id . '" />';
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
        return ($required === true || $required == 1) ? 'required="1"' : 'required="0"';
    }

    private static function convertDisabled($disabled)
    {
        return $disabled === true ? 'disabled="disabled"' : '';
    }
}
