<?php use Ubirimi\Container\UbirimiContainer; ?>
<div class="container page-container client-sign-up">
    <?php require_once __DIR__ . '/_signupForm.php' ?>
</div>

<script type="text/javascript" src="/js/lang/translation.js"></script>
<script type="text/javascript" src="/js/signup_payment.js"></script>
<script type="text/javascript">
    var VALIDATE_CVC = true;
    var PAYMILL_PUBLIC_KEY = '<?php echo UbirimiContainer::get()['paymill.public_key'] ?>';
</script>

<script type="text/javascript" src="https://bridge.paymill.com/"></script>

<script type="text/javascript" src="/js/creditcardBrandDetection.js"></script>
<br />
<br />