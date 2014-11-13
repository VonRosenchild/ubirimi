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

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class EditMetadataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowId = $request->get('id');

        $workflow = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);
        $workflowIssueTypeSchemes = $this->getRepository(IssueTypeScheme::class)->getByClientId($session->get('client/id'), 'workflow');

        if ($workflow['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;

        if ($request->request->has('edit_workflow')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $workflowIssueTypeSchemeId = $request->request->get('workflow_issue_type_scheme');

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(Workflow::class)->updateMetaDataById($workflowId, $name, $description, $workflowIssueTypeSchemeId, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Workflow ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/workflows');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/workflow/EditMetadata.php', get_defined_vars());
    }
}
