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

namespace Ubirimi\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;

/**
 * Warm up data in the session needed in a general context: Client or Customer.
 *
 */
class WarmUpService extends UbirimiService
{
    /**
     * Warms up the session with general information
     *
     * @param SessionInterface $session
     * @param $userData some general data about the user
     * @param null $yongoSettings
     * @param null $documentatorSettings
     */
    public function warmUpClient($userData, $warmYongoSettings = false, $warmDocumentadorSettings = false) {
        /* this is needed because of closure use */
        $session = $this->session;
        $this->warmUp($session, $userData);

        $clientProducts = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProducts($userData['client_id'], 'array');

        array_walk($clientProducts, function($value, $key) use ($session) {
            $session->set("client/products/{$key}", $value);
        });

        /**
         * each product session information is under its namespace
         */
        if (true === $warmYongoSettings) {
            $yongoSettings = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getYongoSettings($userData['client_id']);

            array_walk($yongoSettings, function($value, $key) use ($session) {
                $session->set("yongo/settings/{$key}", $value);
            });
        }

        if (true === $warmDocumentadorSettings) {
            $documentadorSettings = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getDocumentadorSettings($userData['client_id']);

            array_walk($documentadorSettings, function($value, $key) use ($session) {
                $session->set("documentador/settings/{$key}", $value);
            });
        }

        /**
         * determine the selected project and product id
         */
        $projectsArray = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjectsByPermission(
            $userData['client_id'],
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS,
            'array'
        );

        if ($projectsArray) {
            $session->set('selected_project_id', $projectsArray[0]['id']);
        }

        $session->set('selected_product_id', $session->get('client/products/sys_product_id'));

        $hasYongoGlobalAdministrationPermission = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasYongoGlobalSystemAdministrationPermission = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasYongoAdministerProjectsPermission = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjectsByPermission($session->get('client/id'), $session->get('user/id'), Permission::PERM_ADMINISTER_PROJECTS);

        $session->set('user/yongo/is_global_administrator', $hasYongoGlobalAdministrationPermission);
        $session->set('user/yongo/is_global_system_administrator', $hasYongoGlobalSystemAdministrationPermission);
        $session->set('user/yongo/is_global_project_administrator', $hasYongoAdministerProjectsPermission);

        $hasDocumentadorGlobalAdministrationPermission = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_ADMINISTRATOR);
        $hasDocumentadorGlobalSystemAdministrationPermission = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasDocumentadorGlobalCreateSpace = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_CREATE_SPACE);

        $session->set('user/documentator/is_global_administrator', $hasDocumentadorGlobalAdministrationPermission);
        $session->set('user/documentator/is_global_system_administrator', $hasDocumentadorGlobalSystemAdministrationPermission);
        $session->set('user/documentator/is_global_create_space', $hasDocumentadorGlobalCreateSpace);
    }

    /**
     * Warms up session data for a customer using HelpDesk
     *
     * @param SessionInterface $session
     * @param $userData
     */
    public function warmUpCustomer($userData) {
        $this->warmUp($this->session, $userData);

        $this->session->set('client/products', array(array('sys_product_id' => SystemProduct::SYS_PRODUCT_HELP_DESK)));

        $projects = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjects($userData['client_id'], 'array', null, true);
        if ($projects) {
            $this->session->set('selected_project_id', $projects[0]['id']);
        } else {
            $this->session->set("selected_product_id", SystemProduct::SYS_PRODUCT_HELP_DESK);
        }
    }

    /**
     * Warms up standard data that is general to both cases.
     *
     * @param $userData
     */
    private function warmUp($session, $userData)
    {
        $clientData = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getById($userData['client_id']);
        $clientSettings = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getSettings($userData['client_id']);
        $clientSmtpSettings = UbirimiContainer::get()['repository']->get(SMTPServer::class)->getByClientId($userData['client_id']);

        /**
         * store user record information in session under "user" namespace
         */
        array_walk($userData, function($value, $key) use ($session) {
            $session->set("user/{$key}", $value);
        });

        array_walk($clientData, function($value, $key) use ($session) {
            $session->set("client/{$key}", $value);
        });

        array_walk($clientSettings, function($value, $key) use ($session) {
            $session->set("client/settings/{$key}", $value);
        });

        if ($clientSmtpSettings) {
            array_walk($clientSmtpSettings, function ($value, $key) use ($session) {
                $session->set("client/settings/smtp/{$key}", $value);
            });
        }
    }
}
