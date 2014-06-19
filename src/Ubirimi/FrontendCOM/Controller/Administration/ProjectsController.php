<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;

class ProjectsController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $projects = Project::getAll(array('sort_by' => 'project.date_created', 'sort_order' => 'desc'));

        $selectedOption = 'projects';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Projects.php', get_defined_vars());
    }
}
