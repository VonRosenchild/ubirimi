<?php
    use Ubirimi\Container\UbirimiContainer;
?>
<div class="container page-container profile" style="width: 1000px">
    <p class="leading">
        <?php require_once __DIR__ . '/../_menu.php' ?>
    </p>
</div>

<?php  if ($session->getFlashBag()->has('payment.transaction.failed')): ?>
    <?php if (true === $session->getFlashBag()->get('payment.transaction.failed')[0]): ?>
        <span style="color: #ff0000">
        There was an error while processing your payment. Please try again later. You have not been charged.
        </span>
    <?php endif ?>
<?php endif ?>
<table>
    <tr>
        <td valign="top">
            <div>
                <?php require_once __DIR__ . '/_paymentForm.php' ?>
            </div>
        </td>
        <td>
            <div style="float: left; margin-left: 20px; height: 500px; vertical-align: middle;">
                Here, you have the possibility to pay your <strong>monthly subscription</strong> of <storng><?php echo $amount ?> USD</storng>.

                <ul style="line-height: 35px;">
                    <li>
                        The name of the credit card receipt will be: SC UBIRIMI 137 SRL
                    </li>
                    <li>
                        In case of a successful payment, your account status will be updated immediately.
                    </li>
                    <li>
                        All payment data is securely handled by our payment service provider (<a class="link" target="_blank" href="https://www.paymill.com/">PAYMILL</a>)
                        <br />
                        and is SSL encrypted to meet <a class="link" target="_blank" href="https://www.pcisecuritystandards.org/organization_info/index.php">PCI DSS level 1</a> compliance. <br />
                    </li>
                    <li>
                        We never have access to or store your credit card data.
                    </li>
                    <li>
                        After a successful payment an invoice will be immediately available.
                    </li>
                    <li>
                        By clicking the Pay button, you agree to the <a class="link" href="">Terms of Service</a>, Privacy and <a target="_blank" class="link" href="">Refund</a> policies.
                    </li>
                    <li>
                        <div style="float: left;">We accept the following credit cards</div>
                        <div style="float: left; margin-left: 7px;">
                    <span>
                        <img src="/img/payment/cc/visa.png" />
                        <img src="/img/payment/cc/visa-electron.png" />
                        <img src="/img/payment/cc/mastercard.png" />
                        <img src="/img/payment/cc/maestro.png" />
                    </span>
                        </div>

                    </li>
                </ul>
            </div>
        </td>
    </tr>
</table>

<script type="text/javascript" src="/js/payment.js"></script>
<script type="text/javascript">
    var VALIDATE_CVC = true;
    var PAYMILL_PUBLIC_KEY = '<?php echo UbirimiContainer::get()['paymill.public_key'] ?>';
</script>

<script type="text/javascript" src="https://bridge.paymill.com/"></script>
<script type="text/javascript" src="/js/lang/translation.js"></script>
<script type="text/javascript" src="/js/creditcardBrandDetection.js"></script>


<link rel="stylesheet" type ="text/css" href="/css/paymill_styles.css">