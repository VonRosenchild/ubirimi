<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Container\UbirimiContainer;

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

                $this->getRepository('yongo.issue.attachment')->deleteById($attachmentId);

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