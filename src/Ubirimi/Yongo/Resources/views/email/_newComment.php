<?php
    use Ubirimi\Container\UbirimiContainer;

    require_once __DIR__ . '/_header.php';
    $session = UbirimiContainer::get()['session'];
?>

<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">
    <a style="text-decoration: none; " href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>"><?php echo $this->issue['summary'] ?></a>
    <br />
</div>
<table width="100%" cellpadding="2" border="0">
    <tr>
        <td>New comment by <?php echo $this->user['first_name'] . ' ' . $this->user['last_name'] ?></td>
    </tr>
    <tr>
        <td><?php echo $this->content ?></td>
    </tr>
</table>

<?php require '_footer.php' ?>