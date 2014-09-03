<?php

namespace Ubirimi\HelpDesk\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\Queue;
use Ubirimi\Repository\HelpDesk\SLA;
use Ubirimi\Yongo\Repository\Project\Project;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $projectId = $request->get('id');
        $slaSelectedId = $request->get('sla_id');

        $project = Project::getById($projectId);
        $SLAs = SLA::getByProjectId($projectId);

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'reports';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $queues = Queue::getByProjectId($projectId);

        if ($queues) {
            $queueSelected = $queues->fetch_array(MYSQLI_ASSOC);
        }

        $slaSelected = SLA::getById($slaSelectedId);

        return $this->render(__DIR__ . '/../../Resources/views/report/View.php', get_defined_vars());
    }
}
