<div class="container page-container" style="height: 400px;">

    <h1>Subscribe to our newsletter</h1>

    <table>
        <tr>
            <td>
                <div class="clearfix">
                    <div class="login-left align-left sectionFeature blue">

                        <form action="/subscribe-newsletter" name="contact" method="post" class="standard-form">
                            <fieldset>
                                <label>Email address</label>

                                <input type="text" value="" name="email_address" class="inputText"/>
                                <?php if (isset($validEmailAddress) && !$validEmailAddress): ?>
                                    <div class="HPFieldLabel14Red">Email address is not valid.</div>
                                <?php elseif (isset($isDuplicate) && $isDuplicate): ?>
                                    <div class="HPFieldLabel14Red">Duplicate email address. Please provide another.</div>
                                <?php endif ?>

                                <br />
                                <button class="button_hp blue" type="submit" name="subscribe">Subscribe</button>

                            </fieldset>
                        </form>
                    </div>
                </div>

            </td>
            <td>
                <div class="login-right align-left">
                    <div class="HPFieldLabel25Red">Our promise</div>
                    <ul>
                        <li>We will not spam you</li>
                        <li>Only important news will be delivered</li>
                        <li>You will enjoy our newsletter</li>
                    </ul>
                </div>

            </td>
        </tr>
    </table>
</div>
<br />
<br />
<br />