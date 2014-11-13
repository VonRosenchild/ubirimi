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

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $queueId = $request->request->get('id');
        $projectId = $request->request->get('project_id');

        $this->getRepository(Queue::class)->deleteById($queueId);

        $queues = $this->getRepository(Queue::class)->getByProjectId($projectId);
        $queueToGoId = -1;

        if ($queues) {
            $firstQueue = $queues->fetch_array(MYSQLI_ASSOC);
            $queueToGoId = $firstQueue['id'];
        }

        return new Response('/helpdesk/queues/' . $projectId . '/' . $queueToGoId);
    }
}
