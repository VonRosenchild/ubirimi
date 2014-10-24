<?php

namespace Ubirimi\SVNHosting\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewUserSettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $menuSelectedCategory = 'svn';
        $svnRepoId = $request->get('id');
        $svnRepo = $this->getRepository(SvnRepository::class)->getById($svnRepoId);

        if ($svnRepo['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $userData = $this->getRepository(SvnRepository::class)->getUserByRepoIdAndUserId($svnRepoId, $loggedInUserId);
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_SVN_HOSTING_NAME. ' / ' . $svnRepo['code'] . ' / My Settings';

        return $this->render(__DIR__ . '/../Resources/views/ViewUserSettings.php', get_defined_vars());
    }
}
