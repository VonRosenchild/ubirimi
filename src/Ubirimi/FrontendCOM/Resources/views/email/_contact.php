<?php require_once __DIR__ . '/../../../../General/Resources/views/email/_header.php' ?>

<br /><br />
Name: <?php echo $this->name ?>
<br />
Subject: <?php echo $this->subject ?>
<br />
Email: <?php echo $this->email ?>
<br /><br />
<?php echo $this->message ?>
<br />

<?php require_once __DIR__ . '/../../../../General/Resources/views/email/_footer.php' ?>