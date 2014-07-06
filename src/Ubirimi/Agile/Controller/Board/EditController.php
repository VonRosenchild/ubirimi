<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\AgileBoard;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';
        $projects = Project::getByClientId($session->get('client/id'));

        $boardId = $request->get('id');
        $board = AgileBoard::getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $boardName = $board['name'];
        $boardDescription = $board['description'];

        if ($request->request->has('confirm_new_board')) {
            $boardName = Util::cleanRegularInputField($request->request->get('name'));
            $boardDescription = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($boardName))
                $emptyName = true;

            if (!$emptyName) {

                $date = Util::getServerCurrentDateTime();

                AgileBoard::updateMetadata($session->get('client/id'), $boardId, $boardName, $boardDescription, $date);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_CHEETAH,
                    $session->get('user/id'),
                    'UPDATE Cheetah Agile Board ' . $boardName,
                    $date
                );

                return new RedirectResponse('/agile/boards');
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Update Board';

        return $this->render(__DIR__ . '/../../Resources/views/board/Edit.php', get_defined_vars());
    }
}
