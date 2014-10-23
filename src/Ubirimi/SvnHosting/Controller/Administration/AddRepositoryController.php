<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Event\UserEvent;
use Ubirimi\SvnHosting\Repository\Repository;
use ubirimi\svn\SVNUtils;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddRepositoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'svn';
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);
        $isSVNAdministrator = $session->get('user/svn_administrator_flag');

        $emptyName = false;
        $duplicateName = false;
        $emptyCode = false;
        $duplicateCode = false;

        $clientId = $session->get('client/id');

        if ($request->request->has('confirm_new_svn_repository')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $code = Util::cleanRegularInputField($request->request->get('code'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            if (empty($code)) {
                $emptyCode = true;
            } else {
                $svn_repository_exists = Repository::getByCode(mb_strtolower($code), $clientId);
                if ($svn_repository_exists) {
                    $duplicateCode = true;
                }
            }

            if (!$emptyName && !$emptyCode && !$duplicateName && !$duplicateCode) {
                $currentDate = Util::getServerCurrentDateTime();
                $repoId = Repository::addRepo($clientId, $session->get('user/id'), $name, $description, $code, $currentDate);

                $repoPath = UbirimiContainer::get()['subversion.path'] . Util::slugify($session->get('client/company_domain')) . '/' . Util::slugify($name);
                /* create the repository on disk */
                @mkdir(UbirimiContainer::get()['subversion.path'] . Util::slugify($session->get('client/company_domain')), 0700, true);
                @mkdir(UbirimiContainer::get()['subversion.path'] . Util::slugify($session->get('client/company_domain')) . '/' . Util::slugify($name), 0700, true);

                try {
                    Repository::createSvn($repoPath);
                    SVNUtils::createStandardDirectories($repoPath);

                    /* add the user */
                    Repository::addUser($repoId, $session->get('user/id'));
                    Repository::updateUserPermissions($repoId, $session->get('user/id'), 1, 1);

                    /* apache config */
                    Repository::apacheConfig(Util::slugify($session->get('client/company_domain')), Util::slugify($name));
                }
                catch (\Exception $e) {

                }

                $userEvent = new UserEvent(
                    UserEvent::STATUS_NEW_SVN,
                    $session->get('user/first_name'),
                    $session->get('user/last_name'),
                    $session->get('user/username'),
                    null,
                    $session->get('user/email'),
                    array('svnRepoId' => $repoId, 'svnRepositoryName' => $name)
                );

                UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::USER, $userEvent);

                $svnLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_SVN_HOSTING, 'ADD SVN Repository ' . Util::slugify($name));

                UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $svnLogEvent);

                return new RedirectResponse('/svn-hosting/administration/all-repositories');
            }
        }

        return $this->render(__DIR__ . '/../../Resources/views/administration/AddRepository.php', get_defined_vars());
    }
}
