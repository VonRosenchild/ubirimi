<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\HelpDesk\SLACalendar;

class RenderAddGoalController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $time = time();

        $projectId = $request->request->get('project_id');
        $slaCalendars = SLACalendar::getByProjectId($projectId);

        return $this->render(__DIR__ . '/../../Resources/views/sla/RenderAddGoal.php', get_defined_vars());
    }
}
