<?php
    use Ubirimi\Container\UbirimiContainer;

    require_once __DIR__ . '/_header.php';
    $session = UbirimiContainer::get()['session'];
?>

<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">
    <a style="text-decoration: none; " href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>"><?php echo $this->issue['summary'] ?></a>
</div>

<table width="100%" cellpadding="2" border="0">
    <?php for ($index = 0; $index < count($this->fieldChanges); $index++): ?>
    <?php $fieldChanged = ucfirst(str_replace('_', ' ', $this->fieldChanges[$index][0])) ?>
    <tr>
        <?php if ($this->fieldChanges[$index][0] == 'comment'): ?>
            <td><?php echo ucfirst($fieldChanged) . ': ' . $this->fieldChanges[$index][1] ?></td>
        <?php else: ?>
            <?php
                $granularity = new cogpowered\FineDiff\Granularity\Word;
                $diff = new cogpowered\FineDiff\Diff($granularity);

                $opCodes = $diff->getOpcodes($this->fieldChanges[$index][1], $this->fieldChanges[$index][2]);

                $render = new cogpowered\FineDiff\Render\Html;
                $diffHTML = $render->process($this->fieldChanges[$index][1], $opCodes);

                $diffHTML = str_replace('<ins>', '<ins style="color: green; background: #dfd; text-decoration: none;">', $diffHTML);
                $diffHTML = str_replace('<del>', '<del style="color: red;background: #fdd;	text-decoration: none;">', $diffHTML);
            ?>
            <td>
                <?php echo ucfirst($fieldChanged) . ': ' . htmlspecialchars_decode($diffHTML) ?>
            </td>
        <?php endif ?>
    </tr>
    <?php endfor ?>
</table>

<?php require __DIR__ . '/_footer.php' ?>