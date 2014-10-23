<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $projectId = $request->get('id');
        $slaSelectedId = $request->get('sla_id');

        $project = $this->getRepository('yongo.project.project')->getById($projectId);
        $SLAs = $this->getRepository('helpDesk.sla.sla')->getByProjectId($projectId);

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla';
        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $queues = $this->getRepository('helpDesk.queue.queue')->getByProjectId($projectId);
        if ($queues) {
            $queueSelected = $queues->fetch_array(MYSQLI_ASSOC);
        }
        $slaSelected = $this->getRepository('helpDesk.sla.sla')->getById($slaSelectedId);

        $startConditions = explode("#", $slaSelected['start_condition']);
        $stopConditions = explode("#", $slaSelected['stop_condition']);

        $goals = $this->getRepository('helpDesk.sla.sla')->getGoals($slaSelectedId);
        $allRemainingIssuesDefinitionFound = false;

        return $this->render(__DIR__ . '/../../Resources/views/sla/View.php', get_defined_vars());
    }
}
