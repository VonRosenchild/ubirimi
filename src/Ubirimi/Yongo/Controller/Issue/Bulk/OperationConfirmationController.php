<?php

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
use Ubirimi\Yongo\Repository\Issue\Attachment;

class OperationConfirmationController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'issue';
        $smtpSettings = $session->get('client/settings/smtp');

        $issues = $this->getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => UbirimiContainer::get()['session']->get('bulk_change_issue_ids'), $loggedInUserId));
        if ($request->request->has('confirm')) {

            if (UbirimiContainer::get()['session']->get('bulk_change_operation_type') == 'delete') {
                $issueIds = UbirimiContainer::get()['session']->get('bulk_change_issue_ids');
                for ($i = 0; $i < count($issueIds); $i++) {
                    if (UbirimiContainer::get()['session']->get('bulk_change_send_operation_email')) {
                        $issue = $this->getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueIds[$i]), $loggedInUserId);

                        $issueEvent = new IssueEvent($issue, null, IssueEvent::STATUS_DELETE);
                        $issueLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_YONGO, 'DELETE Yongo issue ' . $issue['project_code'] . '-' . $issue['nr']);

                        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);
                        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $issueLogEvent);
                    }

                    $this->getRepository('yongo.issue.issue')->deleteById($issueIds[$i]);
                    Attachment::deleteByIssueId($issueIds[$i]);

                    // also delete the substaks
                    $childrenIssues = $this->getRepository('yongo.issue.issue')->getByParameters(array('parent_id' => $issueIds[$i]), $loggedInUserId);
                    while ($childrenIssues && $childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)) {
                        $this->getRepository('yongo.issue.issue')->deleteById($childIssue['id']);
                        Attachment::deleteByIssueId($childIssue['id']);
                    }
                }
            }
            return new RedirectResponse('/yongo/issue/search?' . UbirimiContainer::get()['session']->get('bulk_change_choose_issue_query_url'));
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Bulk: Operation Confirmation';

        return $this->render(__DIR__ . '/../../../Resources/views/issue/bulk/OperationConfirmation.php', get_defined_vars());
    }
}