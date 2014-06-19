<div class="ubirimi-payment-container">
    <form id="payment-form" action="/account/payment/process" method="POST" style="float: left;">
        <header>
            <h2 class="form-signin-heading">
                Ubirimi subscription of <span id="charge-amount"><?php echo $amount ?> USD</span>
            </h2>
            <img alt="Logo" src="/img/site/bg.logo.png" />
        </header>
        <div class="payment_errors">&nbsp;</div>
        <fieldset>
            <label for="card-number" class="card-number-label field-left"></label>
            <input id ="card-number" class="card-number field-left" type="text" placeholder="**** **** **** ****" maxlength="19">
            <label for="card-expiry" class="card-expiry-label field-right"></label>
            <input id="card-expiry" class="card-expiry field-right" type="text" placeholder="MM/YY" maxlength="7">
        </fieldset>
        <fieldset>
            <label for="card-holdername" class="card-holdername-label field-left"></label>
            <input id="card-holdername" class="card-holdername field-left" type="text">
            <label for="card-cvc" class="field-right"><span class="card-cvc-label"></span></label>
            <input id="card-cvc" class="card-cvc field-right" type="text" placeholder="CVC" maxlength="4">
        </fieldset>

        <fieldset id="buttonWrapper">
            <button id="paymill-submit-button" class="submit-button btn btn-primary" type="submit"></button>
        </fieldset>
    </form>
    <input type="hidden" id="amount" value="<?php echo $amount ?>" />
</div>
<div style="clear: both;"></div>