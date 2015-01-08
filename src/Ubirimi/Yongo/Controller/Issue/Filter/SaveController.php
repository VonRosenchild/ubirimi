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

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

class SaveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $Id = $request->request->get('id');
        $projectId = $request->request->get('project_id');
        $filterName = $request->request->get('filter_name');
        $filterDescription = $request->request->get('filter_description');
        $filterData = $request->request->get('filter_data');

        $date = Util::getServerCurrentDateTime();

        if ($Id != -1) {
            $this->getRepository(IssueFilter::class)->updateById($Id, $filterName, $filterDescription, $filterData, $date);
            $Id = -1;
            $this->getLogger()->addInfo('UPDATE Yongo filter' . $filterName, $this->getLoggerContext());
        } else {
            $Id = $this->getRepository(IssueFilter::class)->save($loggedInUserId, $filterName, $filterDescription, $filterData, $date);
            $this->getLogger()->addInfo('ADD Yongo filter' . $filterName, $this->getLoggerContext());
        }

        return new Response($Id);
    }
}
