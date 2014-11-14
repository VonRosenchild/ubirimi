<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ManageAppListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $application = $request->request->get('app');
        $visible = $request->request->get('visible');

        $currentDate = Util::getServerCurrentDateTime();

        switch ($application) {
            case 'yongo':
                $productId = SystemProduct::SYS_PRODUCT_YONGO;
                break;
            case 'agile':
                $productId = SystemProduct::SYS_PRODUCT_AGILE;
                break;
            case 'helpdesk':
                $productId = SystemProduct::SYS_PRODUCT_HELP_DESK;
                break;
            case 'events':
                $productId = SystemProduct::SYS_PRODUCT_CALENDAR;
                break;
            case 'documentador':
                $productId = SystemProduct::SYS_PRODUCT_DOCUMENTADOR;
                break;
            case 'svn':
                $productId = SystemProduct::SYS_PRODUCT_SVN_HOSTING;
                break;
        }

        if ($visible) {
            $this->getRepository(UbirimiClient::class)->addProduct($session->get('client/id'), $productId, $currentDate);
        } else {
            $this->getRepository(UbirimiClient::class)->deleteProduct($session->get('client/id'), $productId);
            if ($productId == SystemProduct::SYS_PRODUCT_YONGO) {
                $this->getRepository(UbirimiClient::class)->deleteProduct($session->get('client/id'), SystemProduct::SYS_PRODUCT_HELP_DESK);
                $this->getRepository(UbirimiClient::class)->deleteProduct($session->get('client/id'), SystemProduct::SYS_PRODUCT_AGILE);
            }
        }

        $clientProducts = $this->getRepository(UbirimiClient::class)->getProducts($session->get('client/id'), 'array');

        UbirimiContainer::get()['session']->remove("client/products");

        if (count($clientProducts)) {
            array_walk($clientProducts, function($value, $key) {
                UbirimiContainer::get()['session']->set("client/products/{$key}", $value);
            });
        } else {
            $this->getRepository(UbirimiClient::class)->addProduct($session->get('client/id'), $productId, $currentDate);
            $session->set('client/products', array(array('sys_product_id' => $productId)));
        }

        return new Response('');
    }
}
