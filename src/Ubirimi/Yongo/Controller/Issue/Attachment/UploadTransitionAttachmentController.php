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

use Nyholm\ZebraImage\ZebraImage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;

class UploadTransitionAttachmentController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $loggedInUserId = $session->get('user/id');

        $filenameData = apache_request_headers();
        $filename = rawurldecode($filenameData['X_FILENAME']);

        $issueId = $request->request->get('issue_id');

        if (!$session->has('added_attachments_in_screen')) {
            $session->set('added_attachments_in_screen', array());
        }

        /* every attachment has its own dedicated sub-folder, so no editing on the upload filename will be done */

        if ($filename) {
            $ext = substr($filename, strrpos($filename, '.') + 1);
            $filenameWithoutExtension = substr($filename, 0, strrpos($filename, '.'));

            $attachmentId = $this->getRepository(IssueAttachment::class)->add($issueId,
                Util::slugify($filenameWithoutExtension) . '.' . $ext,
                $loggedInUserId,
                Util::getServerCurrentDateTime());

            if ($issueId == null) {
                $issueId = 'user_' . $loggedInUserId;
            }

            $uploadDirectory = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId;
            /* subfolders */
            $uploadDirSubfolder = $uploadDirectory . '/' . $attachmentId;

            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory);
            }

            if (!file_exists($uploadDirSubfolder)) {
                mkdir($uploadDirSubfolder);
            }

            $newFileName = $uploadDirSubfolder . '/' . Util::slugify($filenameWithoutExtension) . '.' . $ext;

            file_put_contents($newFileName, file_get_contents('php://input'));

            $size = filesize($newFileName);

            $temp = $session->get('added_attachments_in_screen');
            $temp[] = $attachmentId;
            $session->set('added_attachments_in_screen', $temp);

            $this->getRepository(IssueAttachment::class)->updateSizeById($attachmentId, $size);

            if (Util::isImage(Util::getExtension($filename))) {

                $thumbUploaddirSubfolder = $uploadDirSubfolder . '/thumbs';
                if (!file_exists($thumbUploaddirSubfolder)) {
                    mkdir($thumbUploaddirSubfolder);
                }
                $newThumbnailName = $thumbUploaddirSubfolder . '/' . Util::slugify($filenameWithoutExtension) . '.' . $ext;

                $image = new ZebraImage();
                $image->jpeg_quality = 100;
                $image->chmod_value = 0755;
                $image->source_path = $newFileName;
                $image->target_path = $newThumbnailName;

                $image->resize(150, 150, ZEBRA_IMAGE_CROP_CENTER);
            }

            return new JsonResponse($attachmentId);
        }

        return new Response('');
    }
}