<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ubirimi\svn\SVNRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class EditRepositoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $repoId = Util::cleanRegularInputField($request->get('id'));

        $menuSelectedCategory = 'svn';
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        $empty_code = false;
        $duplicate_code = false;

        if ($request->request->has('confirm_edit_svn_repository')) {
            $repoId = Util::cleanRegularInputField($request->request->get('repo_id'));
            $code = Util::cleanRegularInputField($request->request->get('code'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($code)) {
                $empty_code = true;
            }
            else {
                $svn_repository_exists = SVNRepository::getByCode(mb_strtolower($code), $clientId);
                if ($svn_repository_exists) {
                    $duplicate_code = true;
                }
            }

            if (!$empty_code && !$duplicate_code) {
                $date = Util::getServerCurrentDateTime();
                SVNRepository::updateRepo($description, $code, $repoId, $date);

                Log::add($clientId, SystemProduct::SYS_PRODUCT_SVN_HOSTING, $loggedInUserId, 'UPDATE SVN Repository ' . Util::slugify($code), $date);

                return new RedirectResponse('/svn-hosting/administration/all-repositories');
            }
        } else {
            $svnRepo = SVNRepository::getById($request->get('id'));

            if ($svnRepo['client_id'] != $clientId) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }

            $name = $svnRepo['name'];
            $code = $svnRepo['code'];
            $description = $svnRepo['description'];
        }

        return $this->render(__DIR__ . '/../../Resources/views/administration/EditRepository.php', get_defined_vars());
    }
}
