<?php
    use Ubirimi\Repository\SMTPServer;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', -1);
    $menuSelectedCategory = 'general_mail';

    $smtpSettings = SMTPServer::getByClientId($clientId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / SMTP Server Settings';

    require_once __DIR__ . '/../Resources/views/ViewSMTPSettings.php';