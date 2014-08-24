<?php

namespace Ubirimi\Yongo\Controller\Project;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class RenderAddCommentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $issueId = $request->request->get('id');
        $index = $request->request->get('index');

        return $this->render(__DIR__ . '/../../Resources/views/project/RenderAddComment.php', get_defined_vars());
    }
}
