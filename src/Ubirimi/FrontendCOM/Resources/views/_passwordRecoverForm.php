<form action="/recover-password" name="client-account" method="post" class="standard-form">
    <div class="global-msg confirmation" <?php if (!$session->has('password_recover')): ?>style="display:none;" <?php endif ?>>
        Your new password was sent to the email address provided.
    </div>

    <fieldset>
        <label>Your email address</label>
        <input class="inputText" type="text" name="address" value=""/>
        <?php if (isset($exists) && !$exists): ?>
            <div class="error">The address you provided was not found in our system.</div>
        <?php endif ?>
    </fieldset>
</form>