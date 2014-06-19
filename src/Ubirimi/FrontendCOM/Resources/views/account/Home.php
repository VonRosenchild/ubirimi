<div class="container page-container profile" style="width: 1000px; height: 500px;">

    <p class="leading">
        <?php

        require_once __DIR__ . '/_menu.php' ?>
    </p>

    <h3>Hey <?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?>, welcome to the Ubirimi customer portal.</h3>

    <?php if ($installedFlag): ?>
        <h3>Use the link below to access your products.<br />
            <a style="text-decoration: none; color: #666666;" href="<?php echo $clientData['base_url'] ?>"><?php echo $clientData['base_url'] ?></a>
        </h3>
    <?php else: ?>
        <h3>Your instance is being prepared for you. Give us a minute and we will let you know shortly. Thanks</h3>
    <?php endif ?>

    <table>
        <tr>
            <td>
                <table width="200px">
                    <tr>
                        <td align="center"><h3 style="border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0; background-color: #A17BD1;"># Users</h3></td>
                    </tr>
                    <tr>
                        <td height="100px" align="center" bgcolor="#EEEEEE"><h3><?php echo $users->num_rows ?></h3></td>
                    </tr>
                </table>
            </td>
            <td width="20px"></td>
            <td>
                <table width="240px">
                    <tr>
                        <td align="center"><h3 style="border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0; background-color: #A17BD1;">Sign up date</h3></td>
                    </tr>
                    <tr>
                        <td height="100px" align="center" bgcolor="#EEEEEE"><h3><?php echo date('d F Y', strtotime($clientData['date_created'])) ?></h3></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>