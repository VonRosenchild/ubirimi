<?php

namespace Ubirimi\QuickNotes\Controller\Tag;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\QuickNotes\Repository\Tag;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $tagId = $request->get('id');
        $tag = Tag::getById($tagId);

        if ($tag['user_id'] != $session->get('user/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $tagExists = false;

        if ($request->request->has('edit_tag')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            // check for duplication

            $tagDuplicate = Tag::getByNameAndUserId(
                $session->get('user/id'),
                mb_strtolower($name),
                $tagId
            );

            if ($tagDuplicate) {
                $tagExists = true;
            }

            if (!$tagExists && !$emptyName) {
                $date = Util::getServerCurrentDateTime();
                Tag::updateById($tagId, $name, $description, $date);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_QUICK_NOTES,
                    $session->get('user/id'),
                    'UPDATE NOTEBOOK tag ' . $name,
                    $date
                );

                return new RedirectResponse('/quick-notes/tag/all');
            }
        }

        $menuSelectedCategory = 'tags';

        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME
            . ' / Notebook: ' . $tag['name']
            . ' / Update';

        return $this->render(__DIR__ . '/../../Resources/views/Tag/Edit.php', get_defined_vars());
    }
}
