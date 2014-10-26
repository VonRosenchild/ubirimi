<?php

namespace Ubirimi\HelpDesk\Controller\SLA\Calendar;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\HelpDesk\Repository\Sla\SlaCalendar;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

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
        $project = $this->getRepository(YongoProject::class)->getById($projectId);
        $calendars = SlaCalendar::getByProjectId($projectId);

        $SLAs = $this->getRepository(Sla::class)->getByProjectId($projectId);
        if ($SLAs) {
            $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
            $SLAs->data_seek(0);
        }

        return $this->render(__DIR__ . '/../../../Resources/views/sla/calendar/List.php', get_defined_vars());
    }
}
