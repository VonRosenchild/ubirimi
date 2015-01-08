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

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $stepId = $request->get('id');
        $step = $this->getRepository(Workflow::class)->getStepById($stepId);
        $workflowId = $step['workflow_id'];

        $workflowMetadata = $this->getRepository(Workflow::class)->getMetaDataById($workflowId);

        if ($workflowMetadata['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $allProperties = $this->getRepository(Workflow::class)->getSystemWorkflowProperties();
        $emptyValue = false;
        $duplicateKey = false;

        if ($request->request->has('add_property')) {
            $keyId = Util::cleanRegularInputField($request->request->get('key'));
            $value = Util::cleanRegularInputField($request->request->get('value'));

            if (empty($value)) {
                $emptyValue = true;
            }

            if (!$emptyValue) {

                $duplicateKey = $this->getRepository(Workflow::class)->getStepKeyByStepIdAndKeyId($stepId, $keyId);
                if (!$duplicateKey) {
                    $currentDate = Util::getServerCurrentDateTime();
                    $this->getRepository(Workflow::class)->addStepProperty($stepId, $keyId, $value, $currentDate);

                    $this->getLogger()->addInfo('ADD Yongo Workflow Step Property' , $this->getLoggerContext());

                    return new RedirectResponse('/yongo/administration/workflow/view-step-properties/' . $stepId);
                }
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Workflow Step Property';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/step/property/Add.php', get_defined_vars());
    }
}