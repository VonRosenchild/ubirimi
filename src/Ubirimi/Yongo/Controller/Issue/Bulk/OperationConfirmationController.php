<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Event\LogEvent;
    use Ubirimi\Event\UbirimiEvents;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Event\IssueEvent;
    use Ubirimi\Yongo\Event\YongoEvents;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueAttachment;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'issue';
    $smtpSettings = $session->get('client/settings/smtp');

    $issues = Issue::getByParameters(array('issue_id' => UbirimiContainer::get()['session']->get('bulk_change_issue_ids'), $loggedInUserId));
    if (isset($_POST['confirm'])) {

        if (UbirimiContainer::get()['session']->get('bulk_change_operation_type') == 'delete') {
            $issueIds = UbirimiContainer::get()['session']->get('bulk_change_issue_ids');
            for ($i = 0; $i < count($issueIds); $i++) {
                if (UbirimiContainer::get()['session']->get('bulk_change_send_operation_email')) {
                    $issue = Issue::getByParameters(array('issue_id' => $issueIds[$i]), $loggedInUserId);

                    $issueEvent = new IssueEvent($issue, null, IssueEvent::STATUS_DELETE);
                    $issueLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_YONGO, 'DELETE Yongo issue ' . $issue['project_code'] . '-' . $issue['nr']);

                    UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);
                    UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $issueLogEvent);
                }

                Issue::deleteById($issueIds[$i]);
                IssueAttachment::deleteByIssueId($issueIds[$i]);

                // also delete the substaks
                $childrenIssues = Issue::getByParameters(array('parent_id' => $issueIds[$i]), $loggedInUserId);
                while ($childrenIssues && $childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)) {
                    Issue::deleteById($childIssue['id']);
                    IssueAttachment::deleteByIssueId($childIssue['id']);
                }
            }
        }
        header('Location: /yongo/issue/search?' . UbirimiContainer::get()['session']->get('bulk_change_choose_issue_query_url'));
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Bulk: Operation Confirmation';

    require_once __DIR__ . '/../../../Resources/views/issue/bulk/OperationConfirmation.php';