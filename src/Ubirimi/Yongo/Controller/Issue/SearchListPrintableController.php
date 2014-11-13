<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class SearchListPrintableController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');
        $issuesPerPage = $session->get('user/issues_per_page');

        $searchParameters = array();
        $parseURLData = null;

        $getFilter = $request->get('filter');
        $getPage = $request->get('page');
        $getSortColumn = $request->get('sort') ? $request->get('sort') : 'created';
        $getSortOrder = $request->get('order') ? $request->get('order') : 'desc';
        $getSearchQuery = $request->get('search_query');;
        $getSummaryFlag = $request->get('summary_flag');
        $getDescriptionFlag = $request->get('description_flag');
        $getCommentsFlag = $request->get('comments_flag');
        $getProjectIds = $request->get('project') ? explode('|', $request->get('project')) : null;

        if ($getProjectIds) {
            $projectsData = $this->getRepository(YongoProject::class)->getByIds($getProjectIds);
            if (!$projectsData) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
            while ($projectsData && $data = $projectsData->fetch_array(MYSQLI_ASSOC)) {

                if (Util::checkUserIsLoggedIn()) {
                    if ($data['client_id'] != $clientId) {
                        return new RedirectResponse('/general-settings/bad-link-access-denied');
                    }
                } else {
                    $hasBrowsingPermission = $this->getRepository(YongoProject::class)->userHasPermission(array($data['id']), Permission::PERM_BROWSE_PROJECTS);
                    if (!$hasBrowsingPermission) {
                        return new RedirectResponse('/general-settings/bad-link-access-denied');
                    }
                }
            }
        }

        $getAssigneeIds = $request->get('assignee') ? explode('|', $request->get('assignee')) : null;
        $getReportedIds = $request->get('reporter') ? explode('|', $request->get('reporter')) : null;
        $getIssueTypeIds = $request->get('type') ? explode('|', $request->get('type')) : null;
        $getIssueStatusIds = $request->get('status') ? explode('|', $request->get('status')) : null;
        $getIssuePriorityIds = $request->get('priority') ? explode('|', $request->get('priority')) : null;
        $getProjectComponentIds = $request->get('component') ? explode('|', $request->get('component')) : null;
        $getProjectVersionIds = $request->get('version') ? explode('|', $request->get('version')) : null;
        $getIssueResolutionIds = $request->get('resolution') ? explode('|', $request->get('resolution')) : null;

        $getSearchParameters = array('search_query' => $getSearchQuery,
            'summary_flag' => $getSummaryFlag,
            'description_flag' => $getDescriptionFlag,
            'comments_flag' => $getCommentsFlag,
            'project' => $getProjectIds,
            'assignee' => $getAssigneeIds,
            'reporter' => $getReportedIds,
            'filter' => $getFilter,
            'type' => $getIssueTypeIds,
            'status' => $getIssueStatusIds,
            'priority' => $getIssuePriorityIds,
            'component' => $getProjectComponentIds,
            'version' => $getProjectVersionIds,
            'resolution' => $getIssueResolutionIds,
            'sort' => $getSortColumn,
            'sort_order' => $getSortOrder);

        $parseURLData = parse_url($_SERVER['REQUEST_URI']);

        if (!$parseURLData['query']) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if (Util::searchQueryNotEmpty($getSearchParameters)) {

            $issues = $this->getRepository(Issue::class)->getByParameters($getSearchParameters, $loggedInUserId, null, $loggedInUserId);
            $issuesCount = $issues->num_rows;
            $getSearchParameters['link_to_page'] = '/yongo/issue/printable-list';
        }

        $columns = array('code',
            'summary',
            'priority',
            'status',
            'created',
            'updated',
            'reporter',
            'assignee');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Print List';
        $menuSelectedCategory = null;

        return $this->render(__DIR__ . '/../../Resources/views/issue/search/SearchListPrintable.php', get_defined_vars());
    }
}