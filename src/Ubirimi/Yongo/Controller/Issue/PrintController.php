<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;

class PrintController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $Id = $request->get('id');

        $loggedInUserId = $session->get('user/id');
        $clientId = $session->get('client/id');

        if (Util::checkUserIsLoggedIn()) {
            $issue = $this->getRepository('yongo.issue.issue')->getById($Id, $loggedInUserId);
            $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $issue['project_code'] . '-' . $issue['nr'];
            $clientSettings = $session->get('client/settings');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);

            $issue = $this->getRepository('yongo.issue.issue')->getById($Id, $loggedInUserId);
            $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $issue['project_code'] . '-' . $issue['nr'];
        }

        $issueProject = $this->getRepository('yongo.project.project')->getById($issue['issue_project_id']);
        if ($issueProject['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $issueId = $issue['id'];

        $components = $this->getRepository('yongo.issue.component')->getByIssueIdAndProjectId($issueId, $issue['issue_project_id']);
        $versionsAffected = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId($issueId, $issue['issue_project_id'], Issue::ISSUE_AFFECTED_VERSION_FLAG);
        $versionsTargeted = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId($issueId, $issue['issue_project_id'], Issue::ISSUE_FIX_VERSION_FLAG);
        $attachments = $this->getRepository('yongo.issue.attachment')->getByIssueId($issueId);
        $countAttachments = 0;
        if ($attachments) {
            $countAttachments = $attachments->num_rows;
        }

        return $this->render(__DIR__ . '/../../Resources/views/issue/Print.php', get_defined_vars());
    }
}
