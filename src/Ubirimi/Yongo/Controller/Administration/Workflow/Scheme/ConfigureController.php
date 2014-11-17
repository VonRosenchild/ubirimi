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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

class ConfigureController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $emptyName = false;
        $workflowScheme = $this->getRepository(WorkflowScheme::class)->getMetaDataById($Id);

        if ($workflowScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $allWorkflows = $this->getRepository(Workflow::class)->getAllByClientId($session->get('client/id'));

        $schemeWorkflows = $this->getRepository(WorkflowScheme::class)->getDataById($Id);

        $name = $workflowScheme['name'];
        $description = $workflowScheme['description'];

        if ($request->request->has('edit_workflow_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $currentDate = Util::getServerCurrentDateTime();

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $this->getRepository(WorkflowScheme::class)->updateMetaDataById($Id, $name, $description);
                $this->getRepository(WorkflowScheme::class)->deleteDataByWorkflowSchemeId($Id);
                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 9) == 'workflow_') {
                        $workflowId = str_replace('workflow_', '', $key);
                        $this->getRepository(WorkflowScheme::class)->addData($Id, $workflowId, $currentDate);
                    }
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Workflow Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/workflows/schemes');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/scheme/Configure.php', get_defined_vars());
    }
}
