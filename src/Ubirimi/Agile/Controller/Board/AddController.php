<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
;
use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Filter;
use Ubirimi\Yongo\Repository\Permission\Permission;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';
        $projects = $this->getRepository('ubirimi.general.client')->getProjectsByPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS
        );

        $noProjectSelected = false;
        $emptyName = false;

        if ($request->request->has('confirm_new_board')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $projectsInBoard = $request->request->get('project');

            if (!$projectsInBoard)
                $noProjectSelected = true;

            if (empty($name))
                $emptyName = true;

            if (!$emptyName && !$noProjectSelected) {
                $definitionData = 'project=' . implode('|', $projectsInBoard);
                $date = Util::getServerCurrentDateTime();

                $filterId = Filter::save(
                    $session->get('user/id'),
                    'Filter for ' . $name,
                    'Filter created automatically for agile board ' . $name,
                    $definitionData,
                    $date
                );

                $board = new Board($session->get('client/id'), $filterId, $name, $description, $projectsInBoard);
                $currentDate = Util::getServerCurrentDateTime();
                $boardId = $board->save($session->get('user/id'), $currentDate);
                $board->addDefaultColumnData($session->get('client/id'), $boardId);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_CHEETAH,
                    $session->get('user/id'),
                    'ADD Cheetah Agile Board ' . $name,
                    $date
                );

                return new RedirectResponse('/agile/boards');
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Create Board';

        return $this->render(__DIR__ . '/../../Resources/views/board/Add.php', get_defined_vars());
    }
}
