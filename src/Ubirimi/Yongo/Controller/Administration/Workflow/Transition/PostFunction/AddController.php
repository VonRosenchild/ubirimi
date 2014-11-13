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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workflowDataId = $request->get('id');
        $workflowData = $this->getRepository(Workflow::class)->getDataById($workflowDataId);
        $workflow = $this->getRepository(Workflow::class)->getMetaDataById($workflowData['workflow_id']);

        $postFunctions = $this->getRepository(WorkflowFunction::class)->getAll();

        $errors = array('no_function_selected' => false);

        if ($request->request->has('add_new_post_function')) {
            $functionId = $request->request->get('function');
            if ($functionId) {
                return new RedirectResponse('/yongo/administration/workflow/transition-add-post-function-data/' . $workflowDataId . '?function_id=' . $functionId);
            } else {
                $errors['no_function_selected'] = true;
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Post Function';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/Add.php', get_defined_vars());
    }
}