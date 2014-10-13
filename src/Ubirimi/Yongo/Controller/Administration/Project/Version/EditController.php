<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Version;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\Log;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $versionId = $request->get('id');
        $version = $this->getRepository('yongo.project.project')->getVersionById($versionId);
        $projectId = $version['project_id'];
        $project = $this->getRepository('yongo.project.project')->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $alreadyExists = false;

        if ($request->request->has('edit_release')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $releaseDuplicate = $this->getRepository('yongo.project.project')->getVersionByName($projectId, $name, $versionId);
            if ($releaseDuplicate)
                $alreadyExists = true;

            if (!$emptyName && !$alreadyExists) {
                $currentDate = Util::getServerCurrentDateTime();
                Project::updateVersionById($versionId, $name, $description, $currentDate);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Project Version ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/project/versions/' . $projectId);
            }
        }

        $menuSelectedCategory = 'project';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project Version';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/version/Edit.php', get_defined_vars());
    }
}
