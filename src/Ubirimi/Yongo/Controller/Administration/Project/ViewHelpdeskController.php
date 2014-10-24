<?php

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;


class ViewHelpdeskController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $SLAs = $this->getRepository(Sla::class)->getByProjectId($projectId);
        if ($SLAs) {
            $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
            $SLAs->data_seek(0);
        }

        $queues = $this->getRepository(Queue::class)->getByProjectId($projectId);
        $queueSelectedId = -1;
        if ($queues) {
            $queue = $queues->fetch_array(MYSQLI_ASSOC);
            $queueSelectedId = $queue['id'];
        }

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $project['name'];

        return $this->render(__DIR__ . '/../../../Resources/views/administration/project/ViewHelpdesk.php', get_defined_vars());
    }
}