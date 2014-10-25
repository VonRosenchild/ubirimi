<?php
use Ubirimi\Container\UbirimiContainer;

require __DIR__ . '/_header.php';
    $session = UbirimiContainer::get()['session'];
?>

<br />
<?php echo $this->userThatShares['first_name'] . ' ' . $this->userThatShares['last_name'] ?> just shared

<a style="text-decoration: none;"
   href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>">
        <?php echo $this->issue['project_code'] ?>-<?php echo $this->issue['nr'] ?>
</a> with you

<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">
    <a style="text-decoration: none; " href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>"><?php echo $this->issue['summary'] ?></a>
</div>
<br />

<div style="background-color: #DDDDDD"><?php echo str_replace("\n", '<br />', $this->noteContent); ?></div>
<br />
<div>
    <a style="text-decoration: none;" href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>">View Issue</a>
</div>

<?php require __DIR__ . '/_footer.php' ?>