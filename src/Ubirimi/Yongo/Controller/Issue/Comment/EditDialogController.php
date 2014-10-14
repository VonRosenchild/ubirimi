<?php

namespace Ubirimi\Yongo\Controller\Issue\Comment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Comment;

class EditDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $commentId = $request->get('id');

        $comment = $this->getRepository('yongo.issue.comment')->getById($commentId);

        return $this->render(__DIR__ . '/../../../Resources/views/issue/comment/editDialog.php', get_defined_vars());
    }
}
