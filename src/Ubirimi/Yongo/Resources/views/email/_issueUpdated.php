<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Field\Field;

$session = UbirimiContainer::get()['session'];
$smallFields = array(Field::FIELD_AFFECTS_VERSION_CODE, Field::FIELD_FIX_VERSION_CODE, Field::FIELD_RESOLUTION_CODE, Field::FIELD_ASSIGNEE_CODE, Field::FIELD_COMPONENT_CODE,
                     Field::FIELD_DUE_DATE_CODE, Field::FIELD_PRIORITY_CODE, Field::FIELD_ISSUE_SECURITY_LEVEL_CODE, Field::FIELD_REPORTER_CODE, Field::FIELD_STATUS_CODE,
                     Field::FIELD_ISSUE_TYPE_CODE);
$smallFieldsCustomIds = array(Field::CUSTOM_FIELD_TYPE_USER_PICKER_MULTIPLE_USER_CODE_ID, Field::CUSTOM_FIELD_TYPE_DATE_PICKER_CODE_ID,
                              Field::CUSTOM_FIELD_TYPE_NUMBER_CODE, Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CHOICE_CODE, Field::CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE_ID);

?>

<div style="background-color: #ffffff; border-radius: 5px; border: #CCCCCC 1px solid; padding: 10px; margin: 10px;">
<?php require __DIR__ . '/_header.php'; ?>

    <div style="font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">
        <a style="text-decoration: none;" href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>"><?php echo $this->issue['summary'] ?></a>
    </div>
    <div style="height: 10px"></div>

    <div>Current Project: <a href="<?php echo $session->get('client/base_url') ?>/yongo/project/<?php echo $this->project['id'] ?>"><?php echo $this->project['name'] ?></a></div>

    <?php for ($index = 0; $index < count($this->fieldChanges); $index++): ?>
        <?php $fieldChanged = ucfirst(str_replace('_', ' ', $this->fieldChanges[$index][0])) ?>
        <div>
            <?php if ($this->fieldChanges[$index][0] == 'comment'): ?>
                <span><?php echo ucfirst($fieldChanged) . ': ' . str_replace("\n", '<br />', $this->fieldChanges[$index][1]) ?></span>
            <?php elseif (in_array($this->fieldChanges[$index][0], $smallFields) || (isset($this->fieldChanges[$index][5]) && in_array($this->fieldChanges[$index][5], $smallFieldsCustomIds))): ?>
                <?php echo ucfirst($fieldChanged) . ': ' ?><span style="text-decoration: line-through; background-color: #F78181;"><?php echo $this->fieldChanges[$index][1] ?></span> <span style="background-color: #BCF5A9;"><?php echo $this->fieldChanges[$index][2] ?></span>
            <?php else: ?>
                <?php
                    $granularity = new cogpowered\FineDiff\Granularity\Word();
                    $diff = new cogpowered\FineDiff\Diff($granularity);

                    $fromText = $this->fieldChanges[$index][1];
                    $toText = $this->fieldChanges[$index][2];
                    $opCodes = $diff->getOpcodes($fromText, $toText);

                    $render = new cogpowered\FineDiff\Render\Html;
                    $diffHTML = $render->process($fromText, $opCodes);

                    $diffHTML = str_replace('<ins>', '<ins style="color: green; background: #dfd; text-decoration: none;">', $diffHTML);
                    $diffHTML = str_replace('<del>', '<del style="color: red;background: #fdd; text-decoration: none;">', $diffHTML);
                ?>
                <span><?php echo ucfirst($fieldChanged) . ': ' . str_replace("\n", '<br />', htmlspecialchars_decode($diffHTML)) ?></span>
            <?php endif ?>
        </div>
    <?php endfor ?>
    <div>Changed by: <a href="<?php echo $session->get('client/base_url') ?>/yongo/user/profile/<?php echo $this->user['id'] ?>"><?php echo $this->user['first_name'] . ' ' . $this->user['last_name'] ?></a></div>
</div>

<?php require __DIR__ . '/_footer.php' ?>