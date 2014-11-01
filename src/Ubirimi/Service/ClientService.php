<?php

namespace Ubirimi\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Util;

class ClientService
{
    public function add($pendingClientData)
    {
        try {
            $conn = UbirimiContainer::get()['db.connection'];

            $data = json_decode($pendingClientData['data'], true);

            $clientId = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->create(
                $data['companyName'],
                $data['companyDomain'],
                $data['baseURL'],
                $data['adminEmail'],
                $data['country'],
                $data['vatNumber'],
                $data['paymillId'],
                UbirimiContainer::get()['repository']->get(UbirimiClient::class)->INSTANCE_TYPE_ON_DEMAND,
                Util::getServerCurrentDateTime()
            );

            // create the user
            $userId = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->createAdministratorUser(
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
            UbirimiContainer::get()['repository']->get(UbirimiUser::class)->updateDisplayColumns($userId, $columns);

            UbirimiContainer::get()['repository']->get(UbirimiClient::class)->install($clientId);
            UbirimiContainer::get()['repository']->get(EmailQueue::class)->add(
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