<?php

namespace Ubirimi\QuickNotes\Controller\Notebook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $duplicateName = false;

        $name = Util::cleanRegularInputField($request->request->get('name'));
        $description = Util::cleanRegularInputField($request->request->get('description'));

        if (empty($name)) {
            $emptyName = true;
        }

        $notebookSameName = Notebook::getByName($session->get('user/id'), $name);
        if ($notebookSameName) {
            $duplicateName = true;
        }

        if (!$emptyName && !$duplicateName) {
            $currentDate = Util::getServerCurrentDateTime();
            $notebookId = Notebook::save($session->get('user/id'), $name, $description, $currentDate);

            Log::add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_CALENDAR,
                $session->get('user/id'),
                'ADD QUICK NOTES notebook ' . $name,
                $currentDate
            );
        }

        return new Response('');
    }
}
