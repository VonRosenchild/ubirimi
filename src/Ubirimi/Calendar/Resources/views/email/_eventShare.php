<?php
use Ubirimi\Container\UbirimiContainer;

require '_header.php';
$dateFrom = $this->event['date_from'];

$month = substr($dateFrom, 5, 2);
if (substr($month, 0, 1) == 0) {
    $month = substr($month, 1, 1);
}
$year = substr($dateFrom, 0, 4);

$source = '/calendar/view/' . $this->event['id'] . '/' . $month . '/' . $year;
$session = UbirimiContainer::get()['session'];
?>

<br />
<?php echo $this->userThatShares['first_name'] . ' ' . $this->userThatShares['last_name'] ?> just shared

<a style="text-decoration: none;"
    href="<?php echo $session->get('client/base_url') ?>/calendar/event/<?php echo $this->event['id'] ?>?source=<?php echo $source ?>">
    <?php echo $this->event['name'] ?>
</a> with you

<br />
<br />
<br />

<div style="background-color: #DDDDDD"><?php echo $this->noteContent ?></div>
<br />
<div>
    <a style="text-decoration: none;" href="<?php echo $session->get('client/base_url') ?>/calendar/event/<?php echo $this->event['id'] ?>?source=<?php echo $source ?>">View Event</a>
</div>

<?php require '_footer.php' ?>