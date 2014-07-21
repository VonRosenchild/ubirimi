<?php
    use Ubirimi\Container\UbirimiContainer;

    $session = UbirimiContainer::get()['session'];
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
                <span><?php echo ucfirst($fieldChanged) . ': ' . $this->fieldChanges[$index][1] ?></span>
            <?php else: ?>
                <?php
                    $granularity = new cogpowered\FineDiff\Granularity\Word;
                    $diff = new cogpowered\FineDiff\Diff($granularity);

                    $opCodes = $diff->getOpcodes($this->fieldChanges[$index][1], $this->fieldChanges[$index][2]);

                    $render = new cogpowered\FineDiff\Render\Html;
                    $diffHTML = $render->process($this->fieldChanges[$index][1], $opCodes);

                    $diffHTML = str_replace('<ins>', '<ins style="color: green; background: #dfd; text-decoration: none;">', $diffHTML);
                    $diffHTML = str_replace('<del>', '&nbsp;<del style="color: red;background: #fdd;	text-decoration: none;">', $diffHTML);
                    $diffHTML = str_replace('</del><ins', '</del>&nbsp;<ins', $diffHTML);

                ?>
                <span><?php echo ucfirst($fieldChanged) . ': ' . htmlspecialchars_decode($diffHTML) ?></span>
            <?php endif ?>
        </div>
    <?php endfor ?>
    <div>Changed by: <a href="<?php echo $session->get('client/base_url') ?>/yongo/user/profile/<?php echo $this->user['id'] ?>"><?php echo $this->user['first_name'] . ' ' . $this->user['last_name'] ?></a></div>
</div>

<?php require __DIR__ . '/_footer.php' ?>