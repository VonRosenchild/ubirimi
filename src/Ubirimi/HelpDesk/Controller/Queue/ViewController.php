<?php

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\Queue;
use Ubirimi\Repository\HelpDesk\Sla;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\Project;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');
        $issuesPerPage = $session->get('user/issues_per_page');

        $projectId = $request->get('id');
        $queueId = $request->get('queue_id');
        $page = $request->get('page', 1);

        $project = $this->getRepository('yongo.project.project')->getById($projectId);
        $queueSelected = Queue::getById($queueId);

        $columns = explode('#', $queueSelected['columns']);

        $SLAs = Sla::getByProjectId($projectId);
        if ($SLAs) {
            $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
            $SLAs->data_seek(0);
        }

        $queues = Queue::getByProjectId($projectId);
        if ($queues) {
            $whereSQL = Issue::prepareWhereClauseFromQueue(
                $queueSelected['definition'],
                $session->get('user/id'),
                $projectId,
                $session->get('client/id')
            );

            $whereSQL = 'issue_main_table.project_id = ' . $projectId . ' AND ' . $whereSQL;

            $getSearchParameters = array();
            $getSearchParameters['page'] = $page;
            $getSearchParameters['issues_per_page'] = 50;

            $issuesResult = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(
                $getSearchParameters,
                $session->get('user/id'),
                $whereSQL,
                $session->get('user/id')
            );

            $issues = $issuesResult[0];

            $issuesCount = $issuesResult[1];
            $countPages = ceil($issuesCount / $issuesPerPage);
            $currentSearchPage = 1;
            $getSearchParameters = array();
            $getSearchParameters['page'] = $page;
            $getSearchParameters['count_pages'] = $countPages;
            $getSearchParameters['link_to_page'] = '/helpdesk/queues/' . $projectId . '/' . $queueId;
        }

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'queue';
        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Help Desks';

        $selectedProductId = $session->get('selected_product_id');
        $cliMode = false;
        return $this->render(__DIR__ . '/../../Resources/views/queue/View.php', get_defined_vars());
    }
}
