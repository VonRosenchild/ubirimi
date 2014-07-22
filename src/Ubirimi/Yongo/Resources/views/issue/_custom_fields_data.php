<?php
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
?>
<tr>
    <td colspan="3">
        <hr size="1"/>
        <table>
            <?php if ($customFieldsData): ?>
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
                                    case Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CHOICE_CODE:
                                        echo $fieldData['value'];
                                        break;
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile ?>
            <?php endif ?>

            <?php
                $usersCustomField = array();
                $fieldName = '';
            ?>
            <?php while ($fieldData = $customFieldsDataUserPickerMultipleUser->fetch_array(MYSQLI_ASSOC)): ?>
                <?php
                    $usersCustomField[] = LinkHelper::getUserProfileLink($fieldData['id'], SystemProduct::SYS_PRODUCT_YONGO, $fieldData['first_name'], $fieldData['last_name']);
                    $fieldName = $fieldData['name'];
                ?>
            <?php endwhile ?>

            <?php if ($customFieldsDataUserPickerMultipleUser): ?>
                <tr>
                    <td width="160px" class="textLabel"><?php echo $fieldName ?>:</td>
                    <td>
                        <?php echo implode(', ', $usersCustomField) ?>
                    </td>
                </tr>
            <?php endif ?>
        </table>
    </td>
</tr>