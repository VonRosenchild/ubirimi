<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Version;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');
        $project = $this->getRepository('yongo.project.project')->getById($projectId);

        $emptyName = false;
        $alreadyExists = false;

        if ($request->request->has('confirm_new_release')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $releasesDuplicate = $this->getRepository('yongo.project.project')->getVersionByName($projectId, $name);
            if ($releasesDuplicate)
                $alreadyExists = true;

            if (!$emptyName && !$alreadyExists) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('yongo.project.project')->addVersion($projectId, $name, $description, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Project Version ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/project/versions/' . $projectId);
            }
        }

        $menuSelectedCategory = 'project';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Project Version';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/version/Add.php', get_defined_vars());
    }
}
