<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Repository\HelpDesk\SLA;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\Project;

class ListIssueController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'home';

        $projectsForBrowsing = Client::getProjects($session->get('client/id'), null, null, true);

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK);

        if ($projectsForBrowsing) {
            $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
            $projectsForBrowsing->data_seek(0);
            $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

            $searchCriteria = Issue::getSearchParameters($projectsForBrowsing, $session->get('client/id'), 1);
            $issuesResult = null;
        }

        if ($request->request->has('search')) {
            $searchParameters = Issue::prepareDataForSearchFromPostGet($projectIds, $request->request->all(), $request->query->all());

            $redirectLink = str_replace("%7C", "|", http_build_query($searchParameters));

            return new RedirectResponse('/helpdesk/customer-portal/tickets?' . $redirectLink);
        } else {
            $getSearchParameters = Issue::prepareDataForSearchFromURL($request->query->all(), 30);
            $getSearchParameters['helpdesk_flag'] = 1;
            // check to see if the project Ids are all belonging to the client
            $getProjectIds = $request->request->has('project') ? explode('|', $request->query->get('project')) : null;
            if ($getProjectIds) {
                for ($pos = 0; $pos < count($getProjectIds); $pos++) {
                    $projectFilter = Project::getById($getProjectIds[$pos]);

                    if ($projectFilter['client_id'] != $session->get('client/id')) {
                        return new RedirectResponse('/general-settings/bad-link-access-denied');
                    }
                }
            }

            $parseURLData = parse_url($_SERVER['REQUEST_URI']);
            $projectsForBrowsing = array(229);
            if (isset($parseURLData['query']) && $projectsForBrowsing) {
                if (Util::searchQueryNotEmpty($getSearchParameters)) {
                    $issuesResult = Issue::getByParameters($getSearchParameters, $session->get('user/id'));

                    $issues = $issuesResult[0];
                    $issuesCount = $issuesResult[1];
                    $countPages = ceil($issuesCount / 30);
                    $getSearchParameters['count_pages'] = $countPages;
                    $getSearchParameters['link_to_page'] = '/helpdesk/customer-portal/tickets';
                }
            }
        }

        $SLAs = SLA::getByProjectIds(array(229));

        $columns = array('code', 'summary', 'priority', 'status', 'created', 'updated', 'reporter', 'assignee', 'settings_menu');
        if (Util::checkUserIsLoggedIn()) {
            $columns = explode('#', $session->get('user/issues_display_columns'));

            $columns[] = 'settings_menu';
            $columns[] = '';
        }

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/ListIssue.php', get_defined_vars());
    }
}
