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

namespace Ubirimi\Api\Controller\Issue;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class MetadataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        UbirimiContainer::get()['api.auth']->auth($request);

        $returnData = array('projects' => array());

        $projects = $this->getRepository(UbirimiClient::class)->getProjectsByPermission(
            $request->get('api_client_id'),
            $request->get('api_user_id'),
            Permission::PERM_CREATE_ISSUE
        );

        foreach ($projects as $project) {
            $issueTypes = $this->getRepository(YongoProject::class)->getIssueTypes($project['id'], 0, 'array');

            foreach ($issueTypes as &$issueType) {
                $screenData = $this->getRepository(YongoProject::class)->getScreenData(
                    array('issue_type_screen_scheme_id' => $project['issue_type_screen_scheme_id']),
                    $issueType['id'],
                    SystemOperation::OPERATION_CREATE,
                    'array'
                );

                foreach ($screenData as $fieldData) {
                    $issueType['fields'][] = array(
                        'id' => $fieldData['field_id'],
                        'name' => $fieldData['field_name'],
                        'description' => $fieldData['description']
                    );
                }
            }

            $project = array(
                'id' => $project['id'],
                'name' => $project['name'],
                'issueTypes' => $issueTypes
            );

            $returnData['projects'][] = $project;

            break;
        }

        return new JsonResponse($returnData);
    }
}
