<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\Repository\SMTPServer;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $smtpServerId = $_GET['id'];
    $smtpServer = SMTPServer::getById($smtpServerId);
    $session->set('selected_product_id', -1);
    $menuSelectedCategory = 'general_mail';

    $emptyName = false;
    $emptyFromAddress = false;
    $emptyEmailPrefix = false;
    $emptyHostname = false;

    if (isset($_POST['edit_smtp'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $fromAddress  = Util::cleanRegularInputField($_POST['from_address']);
        $emailPrefix = Util::cleanRegularInputField($_POST['email_prefix']);
        $protocol = Util::cleanRegularInputField($_POST['protocol']);
        $hostname = Util::cleanRegularInputField($_POST['hostname']);
        $port = Util::cleanRegularInputField($_POST['port']);
        $timeout = Util::cleanRegularInputField($_POST['timeout']);
        $tls = isset ($_POST['tls']) ? 1 : 0;
        $username = Util::cleanRegularInputField($_POST['username']);
        $password = Util::cleanRegularInputField($_POST['password']);

        $date = Util::getServerCurrentDateTime();
        SMTPServer::updateById($smtpServerId, $name, $description, $fromAddress, $emailPrefix, $protocol, $hostname, $port, $timeout, $tls, $username, $password, $date);

        Log::add($clientId, SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $loggedInUserId, 'UPDATE SMTP Server ' . $name, $date);
        $session->set('client/settings/smtp', SMTPServer::getById($smtpServerId));

        header('Location: /general-settings/smtp-settings');
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Update SMTP Server Settings';

    require_once __DIR__ . '/../../Resources/views/smtp/Edit.php';