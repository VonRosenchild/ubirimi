<div class="container page-container recover-user-password">
    <table align="center">
        <tr>
            <td>
                <h1>Recover my password</h1>

                <div class="clearfix">

                    <p>
                        Simply enter the email address associated with your account below <br />
                        and a new password will be sent to your email address immediately.
                    </p>
                    <div class="align-left">
                        <?php require_once __DIR__ . '/_passwordRecoverForm.php' ?>

                        <button class="button_hp blue" type="button" name="retrieve">Restore Password</button>
                        <img id="loader" src="/img/loader.gif" style="display: none;"/>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>