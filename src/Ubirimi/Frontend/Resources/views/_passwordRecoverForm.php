<form action="/recover-password/do" name="user-account" method="post" class="standard-form">

    <fieldset>
        <label>Your email address</label> <input class="inputText" type="text" name="address" value=""/>
        <?php if (isset($errorNotInClientDomain) && $errorNotInClientDomain): ?>
            <br/>
            <div class="error">Something went wrong.</div>
        <?php elseif (isset($emailAddressNotExists) && $emailAddressNotExists): ?>
            <br/>
            <div class="error">The address you provided was not found in our system.</div>
        <?php endif ?>
    </fieldset>

    <button class="button_hp blue" type="submit" name="retrieve">Restore Password</button>
    <button class="button_hp blue" type="submit" name="go_back">Go back</button>
    <img id="loader" src="/img/loader.gif" style="display: none;"/>

</form>