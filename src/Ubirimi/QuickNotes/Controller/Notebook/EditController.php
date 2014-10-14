<?php

namespace Ubirimi\QuickNotes\Controller\Notebook;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\SystemProduct;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notebookId = $request->get('id');
        $notebook = Notebook::getById($notebookId);

        if ($notebook['user_id'] != $session->get('user/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $notebookExists = false;

        if ($request->request->has('edit_notebook')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            // check for duplication

            $notebookDuplicate = Notebook::getByName(
                $session->get('user/id'),
                mb_strtolower($name),
                $notebookId
            );

            if ($notebookDuplicate) {
                $notebookExists = true;
            }

            if (!$notebookExists && !$emptyName) {
                $date = Util::getServerCurrentDateTime();
                Notebook::updateById($notebookId, $name, $description, $date);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_QUICK_NOTES,
                    $session->get('user/id'),
                    'UPDATE NOTEBOOK notebook ' . $name,
                    $date
                );

                return new RedirectResponse('/quick-notes/my-notebooks');
            }
        }

        $menuSelectedCategory = 'notebooks';

        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME
            . ' / Notebook: ' . $notebook['name']
            . ' / Update';

        return $this->render(__DIR__ . '/../../Resources/views/Notebook/Edit.php', get_defined_vars());
    }
}
