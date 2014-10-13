<?php

namespace Ubirimi\Yongo\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;
use Nyholm\ZebraImage\ZebraImage;
use Ubirimi\Container\UbirimiContainer;

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

            $this->getRepository('ubirimi.user.user')->updateAvatar($originalFileName, $userId);

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
