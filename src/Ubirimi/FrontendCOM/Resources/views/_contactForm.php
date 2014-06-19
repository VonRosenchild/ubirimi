<form class="standard-form" action="/contact-send" method="post" name="contact">

    <div class="global-msg confirmation" <?php if (!$session->has('contact_send')): ?>style="display: none;" <?php endif ?>>
        Thank you for you message. We will get back to you.
    </div>

    <div class="form-section clearfix sectionFeature blue" style="width: 540px">
        <fieldset>
            <label for="name">Your name</label>
            <input id="name" type="text" name="name">
        </fieldset>
        <fieldset>
            <label for="email">Your email</label>
            <input id="email" type="text" name="email" value="<?php if (isset($email)) echo $email ?>">
            <?php if ($errors['empty_email'] || $errors['email_not_valid']): ?>
                <p class="error">Email empty or not valid.</p>
            <?php endif ?>
        </fieldset>
        <fieldset>
            <label for="concerning">Concerning</label>
            <div class="custom-select-container">
                <select id="concerning" name="category">
                    <option selected>Feature request</option>
                    <option selected>Other</option>
                </select>
            </div>
        </fieldset>

        <fieldset>
            <label for="message">Your message</label>
            <textarea id="message" name="message"><?php if (isset($message)) echo $message ?></textarea>
            <?php if ($errors['empty_message']): ?>
                <div class="error">Empty message</div>
            <?php endif ?>
        </fieldset>
    </div>
</form>