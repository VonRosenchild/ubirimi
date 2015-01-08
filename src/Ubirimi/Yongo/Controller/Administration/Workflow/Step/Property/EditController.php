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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Property;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $stepPropertyId = $request->get('id');

        $stepProperty = $this->getRepository(Workflow::class)->getStepPropertyById($stepPropertyId);
        $step = $this->getRepository(Workflow::class)->getStepById($stepProperty['workflow_step_id']);
        $stepId = $step['id'];
        $workflowId = $step['workflow_id'];

        $workflowMetadata = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);
        $allProperties = $this->getRepository(Workflow::class)->getSystemWorkflowProperties();
        $emptyValue = false;
        $duplicateKey = false;

        $value = $stepProperty['value'];

        if ($request->request->has('edit_property')) {
            $keyId = Util::cleanRegularInputField($request->request->get('key'));
            $value = Util::cleanRegularInputField($request->request->get('value'));

            if (empty($value)) {
                $emptyValue = true;
            }

            if (!$emptyValue) {

                $duplicateKey = $this->getRepository(Workflow::class)->getStepKeyByStepIdAndKeyId($stepId, $keyId, $stepProperty['id']);

                if (!$duplicateKey) {

                    $currentDate = Util::getServerCurrentDateTime();
                    $this->getRepository(Workflow::class)->updateStepPropertyById($stepPropertyId, $keyId, $value, $currentDate);

                    $this->getLogger()->addInfo('UPDATE Yongo Workflow Step Property', $this->getLoggerContext());

                    return new RedirectResponse('/yongo/administration/workflow/view-step-properties/' . $stepId);
                }
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Workflow Step Property';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/Edit.php', get_defined_vars());
    }
}