<?php
    use Ubirimi\Repository\Email\EmailQueue;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'general_mail';
    $session->set('selected_product_id', -1);

    $total = 0;
    $mailsInQueue = EmailQueue::getByClientId($clientId);
    if ($mailsInQueue)
        $total = $mailsInQueue->num_rows;

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Mail Queue';

    require_once __DIR__ . '/../Resources/views/MailQueue.php';