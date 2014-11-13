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

namespace Ubirimi\Yongo\Controller\Issue\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;

class DeleteController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session) {
        Util::checkUserIsLoggedInAndRedirect();

        $attachmentId = $request->request->get('att_id');

        $attachment = $this->getRepository(IssueAttachment::class)->getById($attachmentId);

        $this->getRepository(IssueAttachment::class)->deleteById($attachmentId);

        $pathToAttachment = UbirimiContainer::get()['asset.root_folder'] . UbirimiContainer::get()['asset.yongo_issue_attachments'];

        unlink($pathToAttachment . $attachment['issue_id'] . '/' . $attachment['id'] . '/' . $attachment['name']);
        if (Util::isImage(Util::getExtension($attachment['name']))) {
            unlink($pathToAttachment . $attachment['issue_id'] . '/' . $attachment['id'] . '/thumbs/' . $attachment['name']);
            Util::deleteDir($pathToAttachment . $attachment['issue_id'] . '/' . $attachment['id'] . '/thumbs');
        }

        Util::deleteDir($pathToAttachment . $attachment['issue_id'] . '/' . $attachment['id']);

        // delete the issue folder if necessary
        if (2 == count(scandir($pathToAttachment . $attachment['issue_id']))) {
            Util::deleteDir($pathToAttachment . $attachment['issue_id']);
        }

        return new Response('');
    }
}