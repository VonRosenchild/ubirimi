<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Agile\Repository\Sprint\Sprint;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;


class IndexController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $clients = $this->getRepository(UbirimiClient::class)->getAll();
        $projects = $this->getRepository(YongoProject::class)->getAll();
        $users = $this->getRepository(UbirimiUser::class)->getAll();
        $issues = $this->getRepository(Issue::class)->getAll();
        $spaces = $this->getRepository(Space::class)->getAllForAllClients();
        $entities = $this->getRepository(Entity::class)->getAll();
        $agileBoards = $this->getRepository(Board::class)->getAll();
        $agileSprints = $this->getRepository(Sprint::class)->getAllSprintsForClients();
        $svnRepos = $this->getRepository(SvnRepository::class)->getAll();

        $clientsToday = $this->getRepository(UbirimiClient::class)->getAll(array('today' => true));
        $projectsToday = $this->getRepository(YongoProject::class)->getAll(array('today' => true));
        $usersToday = $this->getRepository(UbirimiUser::class)->getAll(array('today' => true));
        $issuesToday = $this->getRepository(Issue::class)->getAll(array('today' => true));
        $svnReposToday = $this->getRepository(SvnRepository::class)->getAll(array('today' => true));

        $selectedOption = 'statistics';

        $page = null;

        return $this->render(__DIR__ . '/../../Resources/views/administration/Index.php', get_defined_vars());
    }
}
