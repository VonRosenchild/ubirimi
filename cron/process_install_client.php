<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\Repository\GeneralTaskQueue;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;

/* check locking mechanism */
if (file_exists('process_install_client.lock')) {
    $fp = fopen('process_install_client.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for process_install_client task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$conn = UbirimiContainer::get()['db.connection'];

$pendingClients = GeneralTaskQueue::getPendingClients();

if (!empty($pendingClients)) {
    foreach ($pendingClients as $pendingClient) {
        try {
            $conn->autocommit(false);

            $data = json_decode($pendingClient['data'], true);

            $clientId = Client::create($data['companyName'],
                                       $data['companyDomain'],
                                       $data['baseURL'],
                                       $data['adminEmail'],
                                       Client::INSTANCE_TYPE_ON_DEMAND,
                                       Util::getServerCurrentDateTime());

            // create the user
            $userId = User::createAdministratorUser($data['adminFirstName'],
                                                    $data['adminLastName'],
                                                    $data['adminUsername'],
                                                    $data['adminPass'],
                                                    $data['adminEmail'],
                                                    $clientId,
                                                    20, 1, 1,
                                                    Util::getServerCurrentDateTime());

            $columns = 'code#summary#priority#status#created#type#updated#reporter#assignee';
            User::updateDisplayColumns($userId, $columns);

            Client::install($clientId);
            EmailQueue::add($clientId,
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

            GeneralTaskQueue::delete($pendingClient['id']);

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            echo sprintf('client [%s] retuned: [%s]', $data['companyName'], $e->getMessage());
        }
    }
}

$conn->autocommit(true);

if (null !== $fp) {
    fclose($fp);
}