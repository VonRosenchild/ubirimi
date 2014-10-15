<?php

namespace Ubirimi\Documentador\Controller\Comment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UploadAttachmentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $content = Util::cleanRegularInputField($_POST['content']);
        $pageId = $_POST['entity_id'];
        $parentId = $_POST['parent_comment_id'];
        $date = Util::getServerCurrentDateTime();

        EntityComment::addComment($pageId, $loggedInUserId, $content, $date, $parentId);
    }
}