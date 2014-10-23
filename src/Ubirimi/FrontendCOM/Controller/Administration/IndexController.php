<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Agile\Repository\Sprint\Sprint;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\SvnHosting\Repository\Repository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


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
        $svnRepos = Repository::getAll();

        $clientsToday = $this->getRepository('ubirimi.general.client')->getAll(array('today' => true));
        $projectsToday = $this->getRepository('yongo.project.project')->getAll(array('today' => true));
        $usersToday = $this->getRepository('ubirimi.user.user')->getAll(array('today' => true));
        $issuesToday = UbirimiContainer::getRepository('yongo.issue.issue')->getAll(array('today' => true));
        $svnReposToday = Repository::getAll(array('today' => true));

        $selectedOption = 'statistics';

        $page = null;

        return $this->render(__DIR__ . '/../../Resources/views/administration/Index.php', get_defined_vars());
    }
}
