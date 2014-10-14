<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Type;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Type;
use Ubirimi\Yongo\Repository\Issue\Issue;


use Ubirimi\SystemProduct;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $oldId = $request->request->get('id');
        $newId = $request->request->get('new_id');

        if ($newId) {
            $projects = $this->getRepository('ubirimi.general.client')->getProjects($session->get('client/id'), 'array', 'id');
            $this->getRepository('yongo.issue.issue')->updateType($projects, $oldId, $newId);
        }

        $issueType = Type::getById($oldId);
        Type::deleteById($oldId);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Type ' . $issueType['name'],
            $currentDate
        );

        return new Response('');
    }
}
