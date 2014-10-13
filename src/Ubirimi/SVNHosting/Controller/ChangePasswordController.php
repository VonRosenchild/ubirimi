<?php

namespace Ubirimi\SVNHosting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\User;
use ubirimi\svn\SVNRepository;
use Ubirimi\SVNHosting\Event\SVNHostingEvent;
use Ubirimi\SVNHosting\Event\SVNHostingEvents;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ChangePasswordController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'svn';
        $userId = $request->query->get('id', $request->request->get('id'));;
        $repoId = $request->query->get('repo_id', $request->request->get('repo_id'));

        $user = $this->getRepository('ubirimi.user.user')->getById($userId);
        $svnRepo = SVNRepository::getById($repoId);

        $errors = array('empty_password' => false, 'password_mismatch' => false);

        if ($request->request->has('password') || $request->request->has('password_again')) {
            $password = Util::cleanRegularInputField($request->request->get('password'));
            $passwordAgain = Util::cleanRegularInputField($request->request->get('password_again'));

            if (empty($password))
                $errors['empty_password'] = true;

            if ($password != $passwordAgain)
                $errors['password_mismatch'] = true;

            if (Util::hasNoErrors($errors)) {
                SVNRepository::updateUserPassword($session->get('selected_svn_repo_id'), $userId, $password);

                SVNRepository::updateHtpasswd($session->get('selected_svn_repo_id'), $session->get('client/company_domain'));
                SVNRepository::updateAuthz();

                $svnEvent = new SVNHostingEvent($svnRepo['name'], $user, array('password' => $password));
                $logEvent = new LogEvent(SystemProduct::SYS_PRODUCT_SVN_HOSTING, sprintf('SVN Change Password for [%s]', $svnRepo['name']));

                UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $logEvent);
                UbirimiContainer::get()['dispatcher']->dispatch(SVNHostingEvents::PASSWORD_UPDATE, $svnEvent);

                return new Response('1');
            }
        }

        $menuSelectedCategory = 'svn';

        return $this->render(__DIR__ . '/../Resources/views/ChangePassword.php', get_defined_vars());
    }
}
