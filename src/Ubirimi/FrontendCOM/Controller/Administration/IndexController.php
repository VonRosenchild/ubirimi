<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Documentador\Entity;
use Ubirimi\Agile\Repository\AgileBoard;
use Ubirimi\Agile\Repository\AgileSprint;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Documentador\Space;
use Ubirimi\Repository\User\User;
use ubirimi\svn\SVNRepository;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\Project;

class IndexController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $clients = Client::getAll();
        $projects = Project::getAll();
        $users = User::getAll();
        $issues = UbirimiContainer::getRepository('yongo.issue.issue')->getAll();
        $spaces = Space::getAllForAllClients();
        $entities = Entity::getAll();
        $agileBoards = AgileBoard::getAll();
        $agileSprints = AgileSprint::getAllSprintsForClients();
        $svnRepos = SVNRepository::getAll();

        $clientsToday = Client::getAll(array('today' => true));
        $projectsToday = Project::getAll(array('today' => true));
        $usersToday = User::getAll(array('today' => true));
        $issuesToday = UbirimiContainer::getRepository('yongo.issue.issue')->getAll(array('today' => true));
        $svnReposToday = SVNRepository::getAll(array('today' => true));

        $selectedOption = 'statistics';

        $page = null;

        return $this->render(__DIR__ . '/../../Resources/views/administration/Index.php', get_defined_vars());
    }
}
