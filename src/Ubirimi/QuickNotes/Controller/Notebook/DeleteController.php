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

namespace Ubirimi\QuickNotes\Controller\Notebook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\SystemProduct;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notebookId = $request->request->get('id');
        $notebook = $this->getRepository(Notebook::class)->getById($notebookId);

        $date = Util::getServerCurrentDateTime();

        $this->getRepository(Notebook::class)->deleteById($notebookId);

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_QUICK_NOTES,
            $session->get('user/id'),
            'DELETE QUICK NOTES notebook ' . $notebook['name'],
            $date
        );

        return new Response('');
    }
}
