<?php
    use Ubirimi\Yongo\Repository\Field\Field;
?>
<tr>
    <td colspan="3">
        <hr size="1"/>
        <table>
            <?php while ($fieldData = $customFieldsData->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td width="160px" class="textLabel"><?php echo $fieldData['name'] ?>:</td>
                    <td>
                        <?php
                            switch ($fieldData['code']) {
                                case Field::CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE:
                                case Field::CUSTOM_FIELD_TYPE_BIG_TEXT_CODE:
                                    echo $fieldData['value'];
                                    break;
                                case Field::CUSTOM_FIELD_TYPE_DATE_PICKER_CODE:
                                    echo date('j/M/Y', strtotime($fieldData['value']));
                                    break;
                                case Field::CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE:
                                    echo date('j/M/Y H:i', strtotime($fieldData['value']));
                                    break;
                                case Field::CUSTOM_FIELD_TYPE_NUMBER_CODE:
                                    echo $fieldData['value'];
                                    break;
                                case Field::CUSTOM_FIELD_SELECT_LIST_SINGLE_CHOICE:
                                    echo $fieldData['value'];
                                    break;
                            }
                        ?>
                    </td>
                </tr>
            <?php endwhile ?>
        </table>
    </td>
</tr>