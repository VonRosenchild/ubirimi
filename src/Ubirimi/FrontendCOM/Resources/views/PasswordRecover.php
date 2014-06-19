<div class="container page-container recover-account-panel">
    <h1>Recover Password</h1>

    <p>
        Enter the email address associated with your account below<br />
        and a new password will be sent to your email address immediately.
    </p>

    <div class="clearfix">
        <?php require_once __DIR__ . '/_passwordRecoverForm.php' ?>

        <button class="button_hp blue" type="button" name="retrieve">Recover Password</button>
        <img id="loader" src="/img/loader.gif" style="display: none;"/>
    </div>
</div>
