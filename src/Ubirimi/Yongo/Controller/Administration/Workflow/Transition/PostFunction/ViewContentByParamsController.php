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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class ViewContentByParamsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $idFrom = $request->get('id_from');
        $idTo = $request->get('id_to');
        $workflowId = $request->get('workflow_id');

        $allPostFunctions = $this->getRepository(WorkflowFunction::class)->getAll();
        $workflowData = $this->getRepository(Workflow::class)->getDataByStepIdFromAndStepIdTo($workflowId, $idFrom, $idTo);
        $workflowDataId = $workflowData['id'];

        $postFunctions = $this->getRepository(WorkflowFunction::class)->getByWorkflowDataId($workflowDataId);

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/transition/post_function/ViewContentByParams.php', get_defined_vars());
    }
}