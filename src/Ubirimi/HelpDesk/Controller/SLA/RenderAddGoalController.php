<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\SlaCalendar;
use Ubirimi\UbirimiController;

class RenderAddGoalController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $time = time();

        $projectId = $request->request->get('project_id');
        $slaCalendars = SlaCalendar::getByProjectId($projectId);

        return $this->render(__DIR__ . '/../../Resources/views/sla/RenderAddGoal.php', get_defined_vars());
    }
}
