<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Field\Field;

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

            <?php if ($customFieldsDataUserPickerMultipleUser): ?>
                <?php foreach ($customFieldsDataUserPickerMultipleUser as $fieldId => $fieldData): ?>
                    <tr>
                        <td width="160px" class="textLabel"><?php echo $fieldData[0]['field_name'] ?>:</td>
                        <td>
                            <?php $usersCustomField = array() ?>
                            <?php foreach ($fieldData as $userSelected): ?>
                                <?php $usersCustomField[] = LinkHelper::getUserProfileLink($userSelected['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $userSelected['first_name'], $userSelected['last_name']); ?>
                            <?php endforeach ?>
                            <?php echo implode(', ', $usersCustomField) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </table>
    </td>
</tr>