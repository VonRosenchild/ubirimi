<?php
use Ubirimi\Container\UbirimiContainer;

require '_header.php';
$month = date('n');
$year = date('Y');
$session = UbirimiContainer::get()['session'];
?>

<br />
<?php echo $this->userThatShares['first_name'] . ' ' . $this->userThatShares['last_name'] ?> just shared

<a style="text-decoration: none;"
   href="<?php echo $session->get('client/base_url') ?>/calendar/view/<?php echo $this->calendar['id'] ?>/<?php echo $month ?>/<?php echo $year ?>">
    <?php echo $this->calendar['name'] ?>
</a> with you

<br />
<br />
<br />

<div style="background-color: #DDDDDD"><?php echo $this->noteContent ?></div>
<br />
<div>
    <a style="text-decoration: none;" href="<?php echo $session->get('client/base_url') ?>/calendar/view/<?php echo $this->calendar['id'] ?>/<?php echo $month ?>/<?php echo $year ?>">View Calendar</a>
</div>

<?php require '_footer.php' ?>