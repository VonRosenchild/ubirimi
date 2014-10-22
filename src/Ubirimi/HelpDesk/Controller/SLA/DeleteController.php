<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $slaId = $request->request->get('id');
        $projectId = $request->request->get('project_id');

        $this->getRepository('helpDesk.sla.sla')->deleteById($slaId);

        $SLAs = $this->getRepository('helpDesk.sla.sla')->getByProjectId($projectId);
        $slaToGoId = -1;

        if ($SLAs) {
            $firstSLA = $SLAs->fetch_array(MYSQLI_ASSOC);
            $slaToGoId = $firstSLA['id'];
        }

        return new Response('/helpdesk/sla/' . $projectId . '/' . $slaToGoId);
    }
}
