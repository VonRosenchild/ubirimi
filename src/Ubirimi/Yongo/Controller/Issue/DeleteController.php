<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $loggedInUserId = $session->get('user/id');

        Util::checkUserIsLoggedInAndRedirect();
        $issueId = $request->get('issue_id');

        $issue = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $project = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);

        $loggedInUser = $this->getRepository(UbirimiUser::class)->getById($loggedInUserId);
        $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_DELETE, array('loggedInUser' => $loggedInUser));
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);

        $this->getRepository(Issue::class)->deleteById($issueId);

        // also delete the substaks
        $childrenIssues = $this->getRepository(Issue::class)->getByParameters(array('parent_id' => $issueId), $loggedInUserId);
        while ($childrenIssues && $childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)) {
            $this->getRepository(Issue::class)->deleteById($childIssue['id']);
        }

        $issueLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_YONGO, 'DELETE Yongo issue ' . $issue['project_code'] . '-' . $issue['nr']);
        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $issueLogEvent);

        if ($session->has('last_search_parameters')) {
            return new Response(json_encode(array(
                'go_to_search' => true,
                'url' => $session->get('last_search_parameters'))
            ));
        }

        return new Response(json_encode(array('go_to_dashboard' => true)));
    }
}