<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use ubirimi\svn\SVNRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($session->get('client/id'));

        $menuSelectedCategory = 'svn';

        $clientId = $session->get('client/id');

        $svnRepoId = $request->get('id');

        $svnRepo = SVNRepository::getById($svnRepoId);
        if ($svnRepo['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        return $this->render(__DIR__ . '/../../Resources/views/administration/ViewSummary.php', get_defined_vars());
    }
}
