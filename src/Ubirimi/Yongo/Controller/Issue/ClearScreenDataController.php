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

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;

class ClearScreenDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');

        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->request->get('issue_id');

        $attIdsSession = $session->has('added_attachments_in_screen') ? $session->get('added_attachments_in_screen') : null;
        if ($attIdsSession) {
            for ($i = 0; $i < count($attIdsSession); $i++) {

                $attachmentId = $attIdsSession[$i];

                $this->getRepository(IssueAttachment::class)->deleteById($attachmentId);

                if ($issueId) {
                    Util::deleteDir(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId . '/' . $attachmentId);
                }
            }

            $session->remove('added_attachments_in_screen');
        }

        if (file_exists(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . 'user_' . $loggedInUserId)) {
            Util::deleteDir(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . 'user_' . $loggedInUserId);
        }

        return new Response('');
    }
}