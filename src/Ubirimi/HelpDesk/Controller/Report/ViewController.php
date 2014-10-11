<?php

namespace Ubirimi\HelpDesk\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\Queue;
use Ubirimi\Repository\HelpDesk\Sla;
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
        $SLAs = Sla::getByProjectId($projectId);

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'reports';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $queues = Queue::getByProjectId($projectId);

        if ($queues) {
            $queueSelected = $queues->fetch_array(MYSQLI_ASSOC);
        }

        $slaSelected = Sla::getById($slaSelectedId);

        $dateTo = date('Y-m-d');
        $dateFrom = new \DateTime($dateTo, new \DateTimeZone($clientSettings['timezone']));
        $dateFrom = date_sub($dateFrom, date_interval_create_from_date_string('1 months'))->format('Y-m-d');

        return $this->render(__DIR__ . '/../../Resources/views/report/View.php', get_defined_vars());
    }
}
