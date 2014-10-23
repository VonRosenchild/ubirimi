<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class MailQueueController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'general_mail';
        $session->set('selected_product_id', -1);

        $total = 0;
        $mailsInQueue = EmailQueue::getByClientId($clientId);
        if ($mailsInQueue) {
            $total = $mailsInQueue->num_rows;
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Mail Queue';

        return $this->render(__DIR__ . '/../Resources/views/MailQueue.php', get_defined_vars());
    }
}