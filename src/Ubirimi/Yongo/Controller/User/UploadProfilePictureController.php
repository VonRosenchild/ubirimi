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

namespace Ubirimi\Yongo\Controller\User;

use Nyholm\ZebraImage\ZebraImage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UploadProfilePictureController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $userId = $session->get('user/id');
        $uploadPath = UbirimiContainer::get()['asset.root_folder'] . UbirimiContainer::get()['asset.user_avatar'] . $userId;

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath);
        }

        if (UPLOAD_ERR_OK == $_FILES["files"]["error"][0]) {
            $tmp_name = $_FILES["files"]["tmp_name"][0];
            $name = $_FILES["files"]["name"][0];

            $extension = Util::getExtension($name);
            if ($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg') {
                return new Response('error');
            }

            $filename = 'user_' . $userId;
            $originalFileName = $filename . "." . $extension;

            move_uploaded_file($tmp_name, $uploadPath . "/" . $originalFileName);

            $this->getRepository(UbirimiUser::class)->updateAvatar($originalFileName, $userId);

            $newFileName = $uploadPath . "/" . $originalFileName;
            $newThumbnailName = $uploadPath . "/" . $filename . '_150.' . $extension;
            $image = new ZebraImage();
            $image->jpeg_quality = 100;
            $image->chmod_value = 0755;
            $image->source_path = $newFileName;
            $image->target_path = $newThumbnailName;

            $image->resize(150, 150, ZEBRA_IMAGE_CROP_CENTER);

            $newThumbnailName = $uploadPath . "/" . $filename . '_33.' . $extension;
            $image = new ZebraImage();
            $image->jpeg_quality = 100;
            $image->chmod_value = 0755;
            $image->source_path = $newFileName;
            $image->target_path = $newThumbnailName;

            $image->resize(33, 33, ZEBRA_IMAGE_CROP_CENTER);

            $session->set('user/avatar_picture', $filename . '.' . $extension);

            return new Response($userId . '/' . $originalFileName);
        }
    }
}
