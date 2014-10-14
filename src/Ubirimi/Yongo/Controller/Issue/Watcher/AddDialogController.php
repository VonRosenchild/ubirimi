<?php

namespace Ubirimi\Yongo\Controller\Issue\Watcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Yongo\Repository\Issue\Watcher;
use Ubirimi\Yongo\Repository\Permission\Permission;


class AddDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        $clientId = UbirimiContainer::get()['session']->get('client/id');
        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');

        $issueId = $request->request->get('id');

        $issueData = $this->getRepository('yongo.issue.issue')->getByIdSimple($issueId);

        $watchers = Watcher::getByIssueId($issueId);
        // todo: users watchers de aici trebuie sa fie useri ce au permisiune de browsing la proiectul acesta
        $users = $this->getRepository('ubirimi.general.client')->getUsers($clientId);
        $watcherArray = array();

        if ($watchers) {
            while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)) {
                $watcherArray[] = $watcher['id'];
            }
            $watchers->data_seek(0);
        }

        $hasViewVotersAndWatchersPermission = $this->getRepository('yongo.project.project')->userHasPermission($issueData['project_id'], Permission::PERM_VIEW_VOTERS_AND_WATCHERS, $loggedInUserId);
        $hasManageWatchersPermission = $this->getRepository('yongo.project.project')->userHasPermission($issueData['project_id'], Permission::PERM_MANAGE_WATCHERS, $loggedInUserId);

        return $this->render(__DIR__ . '/../../../Resources/views/issue/watcher/AddDialog.php', get_defined_vars());

    }
}