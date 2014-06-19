<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\ProjectCategory;

    Util::checkUserIsLoggedInAndRedirect();

    $projectCategories = ProjectCategory::getAll($clientId);

    $menuSelectedCategory = 'project';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/category/List.php';