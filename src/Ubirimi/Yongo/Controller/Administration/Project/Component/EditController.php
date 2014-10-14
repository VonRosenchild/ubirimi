<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Component;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;



class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $componentId = $request->get('id');
        $component = $this->getRepository('yongo.project.project')->getComponentById($componentId);
        $projectId = $component['project_id'];
        $project = $this->getRepository('yongo.project.project')->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $alreadyExists = false;

        if ($request->request->has('edit_component')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $leader = Util::cleanRegularInputField($request->request->get('leader'));

            if (empty($name))
                $emptyName = true;

            $components_duplicate = $this->getRepository('yongo.project.project')->getComponentByName($projectId, $name, $componentId);
            if ($components_duplicate)
                $alreadyExists = true;

            if (!$emptyName && !$alreadyExists) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository('yongo.project.project')->updateComponentById($componentId, $name, $description, $leader, $currentDate);
                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Project Component ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/project/components/' . $projectId);
            }
        }

        $users = $this->getRepository('ubirimi.general.client')->getUsers($session->get('client/id'));
        $menuSelectedCategory = 'project';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project Component';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/component/Edit.php', get_defined_vars());
    }
}
