<?php

use Ubirimi\Util;

require_once __DIR__ . '/../include/header.php';

    Util::checkUserIsLoggedInAndRedirect();

    $settings = $this->getRepository('ubirimi.general.client')->getYongoSettings($clientId);
    $menuSelectedCategory = 'system';
?>

<body>
    <?php require_once __DIR__ . '/../include/menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">Ubirimi Import</div>
                </td>
            </tr>
        </table>

        <a href="/yongo/import-from-github">Import Data From Github</a>
    </div>
    <?php require_once __DIR__ . '/../include/footer.php' ?>
</body>