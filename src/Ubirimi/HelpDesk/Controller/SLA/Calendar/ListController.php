<?php

namespace Ubirimi\HelpDesk\Controller\SLA\Calendar;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Calendar;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla_calendar';
        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / SLA Calendars';

        $clientId = $session->get('client/id');
        $projectId = $request->get('id');
        $project = $this->getRepository('yongo.project.project')->getById($projectId);
        $calendars = Calendar::getByProjectId($projectId);

        $SLAs = $this->getRepository('helpDesk.sla.sla')->getByProjectId($projectId);
        if ($SLAs) {
            $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
            $SLAs->data_seek(0);
        }

        return $this->render(__DIR__ . '/../../../Resources/views/sla/calendar/List.php', get_defined_vars());
    }
}
