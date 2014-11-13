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
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class PrintController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $Id = $request->get('id');

        $loggedInUserId = $session->get('user/id');
        $clientId = $session->get('client/id');

        if (Util::checkUserIsLoggedIn()) {
            $issue = $this->getRepository(Issue::class)->getById($Id, $loggedInUserId);
            $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $issue['project_code'] . '-' . $issue['nr'];
            $clientSettings = $session->get('client/settings');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);

            $issue = $this->getRepository(Issue::class)->getById($Id, $loggedInUserId);
            $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $issue['project_code'] . '-' . $issue['nr'];
        }

        $issueProject = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);
        if ($issueProject['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $issueId = $issue['id'];

        $components = $this->getRepository(IssueComponent::class)->getByIssueIdAndProjectId($issueId, $issue['issue_project_id']);
        $versionsAffected = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $issue['issue_project_id'], Issue::ISSUE_AFFECTED_VERSION_FLAG);
        $versionsTargeted = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $issue['issue_project_id'], Issue::ISSUE_FIX_VERSION_FLAG);
        $attachments = $this->getRepository(IssueAttachment::class)->getByIssueId($issueId);
        $countAttachments = 0;
        if ($attachments) {
            $countAttachments = $attachments->num_rows;
        }

        return $this->render(__DIR__ . '/../../Resources/views/issue/Print.php', get_defined_vars());
    }
}
