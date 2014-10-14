<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;





use Ubirimi\Repository\User\User;
use ubirimi\svn\SVNRepository;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\Project;

class IndexController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $clients = $this->getRepository('ubirimi.general.client')->getAll();
        $projects = $this->getRepository('yongo.project.project')->getAll();
        $users = $this->getRepository('ubirimi.user.user')->getAll();
        $issues = UbirimiContainer::getRepository('yongo.issue.issue')->getAll();
        $spaces = Space::getAllForAllClients();
        $entities = Entity::getAll();
        $agileBoards = $this->getRepository('agile.board.board')->getAll();
        $agileSprints = Sprint::getAllSprintsForClients();
        $svnRepos = SVNRepository::getAll();

        $clientsToday = $this->getRepository('ubirimi.general.client')->getAll(array('today' => true));
        $projectsToday = $this->getRepository('yongo.project.project')->getAll(array('today' => true));
        $usersToday = $this->getRepository('ubirimi.user.user')->getAll(array('today' => true));
        $issuesToday = UbirimiContainer::getRepository('yongo.issue.issue')->getAll(array('today' => true));
        $svnReposToday = SVNRepository::getAll(array('today' => true));

        $selectedOption = 'statistics';

        $page = null;

        return $this->render(__DIR__ . '/../../Resources/views/administration/Index.php', get_defined_vars());
    }
}
