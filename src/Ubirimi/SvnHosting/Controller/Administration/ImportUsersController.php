<?php

namespace Ubirimi\SvnHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SvnHosting\Event\SvnHostingEvent;
use Ubirimi\SvnHosting\Event\SvnHostingEvents;
use Ubirimi\SvnHosting\Repository\SvnRepository;
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
            $this->getRepository(SvnRepository::class)->addUser($session->get('selected_svn_repo_id'), (int) $userToImport);
            $this->getRepository(SvnRepository::class)->updateUserPermissions($session->get('selected_svn_repo_id'), (int) $userToImport, 1, 1);

            $userRec = $this->getRepository(UbirimiUser::class)->getById((int) $userToImport);
            $svnRepo = $this->getRepository(SvnRepository::class)->getById($session->get('selected_svn_repo_id'));

            $this->getRepository(SvnRepository::class)->updateAuthz();

            $svnEvent = new SvnHostingEvent($svnRepo['name'], $userRec);
            $svnLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_SVN_HOSTING, sprintf('SVN users imported into SVN Repository [%s]', $svnRepo['name']));

            UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $svnLogEvent);
            UbirimiContainer::get()['dispatcher']->dispatch(SvnHostingEvents::IMPORT_USERS, $svnEvent);
        }

        return new Response('');
    }
}
