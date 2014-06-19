<div class="container page-container profile" style="width: 1000px">
    <p class="leading">
        <?php require_once __DIR__ . '/../_menu.php' ?>
    </p>
</div>

<div>
    Thank you.<br />
    We have received your payment and your credit card will soon be charged.<br />
    In <a href="/account/invoices">Invoices<a/> you can view and download the invoice for the payment.<br />
    Just a reminder, you have paid <strong><?php echo $payment['amount'] ?> USD</strong> on <strong><?php echo $paymentDate->format('d') ?> <?php echo $paymentDate->format('F') ?> <?php echo $paymentDate->format('Y') ?></strong>.
    <br />
    <br />
</div>