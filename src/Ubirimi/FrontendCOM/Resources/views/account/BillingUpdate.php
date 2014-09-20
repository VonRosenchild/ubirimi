<?php
    use Ubirimi\Container\UbirimiContainer;
?>
<div class="container page-container profile" style="width: 1000px">
    <p class="leading">
        <?php require_once __DIR__ . '/_menu.php' ?>
    </p>
    <form class="standard-form horizontal" method="post" name="update-billing-details" autocomplete="off" id="update-billing-form" action="/account/billing/update/do">
        <div class="form-section clearfix">
            <h3>Update Payment Details</h3>
            <div class="section-left align-left sectionFeature blue">
                <fieldset>
                    <label for="card_number">Card number</label>
                    <div class="field-extension-container">
                        <input id="card_number" type="text" name="card_number" value="<?php if (isset($_POST['card_number'])) echo $_POST['card_number'] ?>" />
                    </div>
                </fieldset>
                <fieldset>
                    <label for="fname">Expiration date</label>
                    <input style="width: 50px" id="card_exp_month" type="text" name="card_exp_month" value="<?php if (isset($_POST['card_exp_month'])) echo $_POST['card_exp_month'] ?>" />
                    <span style="display: block; float: left">&nbsp;&nbsp;</span>
                    <input style="width: 50px" id="card_exp_year" type="text" name="card_exp_year" value="<?php if (isset($_POST['card_exp_year'])) echo $_POST['card_exp_year'] ?>" />
                </fieldset>
                <fieldset>
                    <label for="card_name">Name on card</label>
                    <input id="card_name" type="text" name="card_name" value="<?php if (isset($_POST['card_name'])) echo $_POST['card_name'] ?>" />
                </fieldset>

                <fieldset>
                    <label for="fname">Security code</label>
                    <input style="width: 100px" id="card_security" type="text" name="card_security" value="<?php if (isset($_POST['card_security'])) echo $_POST['card_security'] ?>" />
                </fieldset>

                <fieldset>
                    <label>Amount to pay</label>
                    <input id="pay_amount" style="width: 100px" type="text" disabled="disabled" value="<?php echo $totalToBeCharged ?>" /> <label style="display: block; width: 10px"> </label> <label>$ / month</label>
                </fieldset>
                <div class="payment_errors error"></div>
            </div>
        </div>

        <button type="submit" class="button_hp_small blue" name="do-update-billing">Update Payment Method</button>
    </form>

    <script type="text/javascript" src="/js/lang/translation.js"></script>
    <script type="text/javascript" src="/js/signup_payment.js"></script>
    <script type="text/javascript">
        var VALIDATE_CVC = true;
        var PAYMILL_PUBLIC_KEY = '<?php echo UbirimiContainer::get()['paymill.public_key'] ?>';
    </script>

    <script type="text/javascript" src="https://bridge.paymill.com/"></script>

    <script type="text/javascript" src="/js/creditcardBrandDetection.js"></script>
</div>

<br />