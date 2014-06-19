<?php
    use Ubirimi\Container\UbirimiContainer;

    require_once __DIR__ . '/_header.php';
    $session = UbirimiContainer::get()['session'];
?>

<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">
    <a style="text-decoration: none; " href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>"><?php echo $this->issue['summary'] ?></a>
    <br />
    <table width="100%" cellpadding="2" border="0">
        <tr>
            <td>Assignee: <span style="text-decoration: line-through; background-color: #F78181;"><?php echo $this->oldUserAssignedName ?></span> <span style="background-color: #BCF5A9;"><?php echo $this->newUserAssignedName ?></span></td>
        </tr>
        <?php if (isset($this->comment)): ?>
            <tr>
                <td>
                    <div>Comment:</div>
                    <?php echo $this->comment ?>
                </td>
            </tr>
        <?php endif ?>
    </table>
</div>

<?php require_once __DIR__ . '/_footer.php' ?>