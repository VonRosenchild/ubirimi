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

namespace Ubirimi\Yongo\Controller\Issue\Bulk;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;

class OperationConfirmationController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $menuSelectedCategory = 'issue';
        $clientSettings = $session->get('client/settings');

        $issues = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => UbirimiContainer::get()['session']->get('bulk_change_issue_ids'), $loggedInUserId));
        if ($request->request->has('confirm')) {

            if (UbirimiContainer::get()['session']->get('bulk_change_operation_type') == 'delete') {
                $issueIds = UbirimiContainer::get()['session']->get('bulk_change_issue_ids');
                for ($i = 0; $i < count($issueIds); $i++) {
                    if (UbirimiContainer::get()['session']->get('bulk_change_send_operation_email')) {
                        $issue = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueIds[$i]), $loggedInUserId);

                        $issueEvent = new IssueEvent($issue, null, IssueEvent::STATUS_DELETE);
                        $this->getLogger()->addInfo('DELETE Yongo issue ' . $issue['project_code'] . '-' . $issue['nr'], $this->getLoggerContext());

                        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);
                                            }

                    $this->getRepository(Issue::class)->deleteById($issueIds[$i]);
                    $this->getRepository(IssueAttachment::class)->deleteByIssueId($issueIds[$i]);

                    // also delete the substaks
                    $childrenIssues = $this->getRepository(Issue::class)->getByParameters(array('parent_id' => $issueIds[$i]), $loggedInUserId);
                    while ($childrenIssues && $childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)) {
                        $this->getRepository(Issue::class)->deleteById($childIssue['id']);
                        $this->getRepository(IssueAttachment::class)->deleteByIssueId($childIssue['id']);
                    }
                }
            }
            return new RedirectResponse('/yongo/issue/search?' . UbirimiContainer::get()['session']->get('bulk_change_choose_issue_query_url'));
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Bulk: Operation Confirmation';

        return $this->render(__DIR__ . '/../../../Resources/views/issue/bulk/OperationConfirmation.php', get_defined_vars());
    }
}