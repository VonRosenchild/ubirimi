<?php

namespace Ubirimi\SvnHosting\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($session->get('client/id'));

        $svnRepoId = $request->get('id');
        $svnRepo = $this->getRepository(SvnRepository::class)->getById($svnRepoId);

        $clientId = $session->get('client/id');

        if ($svnRepo['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $session->set('selected_svn_repo_id', $svnRepoId);

        $menuSelectedCategory = 'svn';
        $i = 0;
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_SVN_HOSTING_NAME. ' / ' . $svnRepo['code'] . ' / Summary';

        return $this->render(__DIR__ . '/../Resources/views/ViewSummary.php', get_defined_vars());
    }
}
