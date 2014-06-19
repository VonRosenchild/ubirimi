<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Email\EmailQueue;

class MailQueueController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $mails = EmailQueue::getAll();

        $selectedOption = 'mail_queue';

        return $this->render(__DIR__ . '/../../Resources/views/administration/MailQueue.php', get_defined_vars());
    }
}
