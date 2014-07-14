<?php

namespace Ubirimi\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;
use Ubirimi\Repository\Email\EmailQueue;

class ClientService
{
    public function add($pendingClientData)
    {
        try {
            $conn = UbirimiContainer::get()['db.connection'];

            $data = json_decode($pendingClientData['data'], true);

            $clientId = Client::create(
                $data['companyName'],
                $data['companyDomain'],
                $data['baseURL'],
                $data['adminEmail'],
                Client::INSTANCE_TYPE_ON_DEMAND,
                Util::getServerCurrentDateTime()
            );

            // create the user
            $userId = User::createAdministratorUser(
                $data['adminFirstName'],
                $data['adminLastName'],
                $data['adminUsername'],
                $data['adminPass'],
                $data['adminEmail'],
                $clientId,
                20, 1, 1,
                Util::getServerCurrentDateTime()
            );

            $columns = 'code#summary#priority#status#created#type#updated#reporter#assignee';
            User::updateDisplayColumns($userId, $columns);

            Client::install($clientId);
            EmailQueue::add(
                $clientId,
                'accounts@ubirimi.com',
                $data['adminEmail'],
                null,
                'Your account details - Ubirimi.com',
                Util::getTemplate('_newAccount.php', array(
                        'username' => $data['adminUsername'],
                        'companyDomain' => $data['companyDomain'],
                        'emailAddress' => $data['adminEmail'])
                ),
                Util::getServerCurrentDateTime());

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollback();

            throw new \Exception(
                sprintf('Could not install client [%s]. Error [%s]', $data['companyName'], $e->getMessage())
            );
        }
    }

    public function delete()
    {

    }
}