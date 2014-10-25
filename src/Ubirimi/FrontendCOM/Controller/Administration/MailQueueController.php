<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class MailQueueController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $mails = $this->getRepository(EmailQueue::class)->getAll();

        $selectedOption = 'mail_queue';

        return $this->render(__DIR__ . '/../../Resources/views/administration/MailQueue.php', get_defined_vars());
    }
}
