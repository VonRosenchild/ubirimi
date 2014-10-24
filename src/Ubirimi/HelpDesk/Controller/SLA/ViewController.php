<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $projectId = $request->get('id');
        $slaSelectedId = $request->get('sla_id');

        $project = $this->getRepository(YongoProject::class)->getById($projectId);
        $SLAs = $this->getRepository(Sla::class)->getByProjectId($projectId);

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla';
        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $queues = $this->getRepository(Queue::class)->getByProjectId($projectId);
        if ($queues) {
            $queueSelected = $queues->fetch_array(MYSQLI_ASSOC);
        }
        $slaSelected = $this->getRepository(Sla::class)->getById($slaSelectedId);

        $startConditions = explode("#", $slaSelected['start_condition']);
        $stopConditions = explode("#", $slaSelected['stop_condition']);

        $goals = $this->getRepository(Sla::class)->getGoals($slaSelectedId);
        $allRemainingIssuesDefinitionFound = false;

        return $this->render(__DIR__ . '/../../Resources/views/sla/View.php', get_defined_vars());
    }
}
