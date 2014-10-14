<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Version;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Project\Version;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $releaseId = $request->request->get('release_id');
        $release = $this->getRepository('yongo.project.project')->getVersionById($releaseId);

        Version::deleteVersionById($releaseId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Project Version ' . $release['name'],
            $currentDate
        );

        return new Response('');
    }
}
