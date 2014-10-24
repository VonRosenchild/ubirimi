<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Version;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $releaseId = $request->request->get('release_id');
        $release = $this->getRepository(YongoProject::class)->getVersionById($releaseId);

        $this->getRepository(IssueVersion::class)->deleteVersionById($releaseId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Project Version ' . $release['name'],
            $currentDate
        );

        return new Response('');
    }
}
