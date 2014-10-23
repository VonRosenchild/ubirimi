<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\SvnHosting\Repository\Repository;
use Ubirimi\SVNHosting\Event\SVNHostingEvent;
use Ubirimi\SVNHosting\Event\SVNHostingEvents;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ImportUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $usersIdsToImport = $request->request->get('users');

        foreach ($usersIdsToImport as $userToImport) {
            Repository::addUser($session->get('selected_svn_repo_id'), (int) $userToImport);
            Repository::updateUserPermissions($session->get('selected_svn_repo_id'), (int) $userToImport, 1, 1);

            $userRec = $this->getRepository('ubirimi.user.user')->getById((int) $userToImport);
            $svnRepo = Repository::getById($session->get('selected_svn_repo_id'));

            Repository::updateAuthz();

            $svnEvent = new SVNHostingEvent($svnRepo['name'], $userRec);
            $svnLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_SVN_HOSTING, sprintf('SVN users imported into SVN Repository [%s]', $svnRepo['name']));

            UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $svnLogEvent);
            UbirimiContainer::get()['dispatcher']->dispatch(SVNHostingEvents::IMPORT_USERS, $svnEvent);
        }

        return new Response('');
    }
}
