<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\Project;

class IssueController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $issuesResult = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array(
                'sort' => 'created',
                'sort_order' => 'desc',
                'issues_per_page' => 500,
                'page' => 1)
        );

        Project::getById("issue_project_id");

        $issues = $issuesResult[0];
        $selectedOption = 'issues';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Issue.php', get_defined_vars());
    }
}
