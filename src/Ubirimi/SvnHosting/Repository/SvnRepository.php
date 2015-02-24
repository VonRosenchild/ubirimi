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

namespace Ubirimi\SvnHosting\Repository;

use Exception;
use Path;
use Ubirimi\ConsoleUtils;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use ubirimi\svn\SVNCrypt;
use ubirimi\svn\SVNUtils;
use Ubirimi\Util;

class SvnRepository
{
    public function getByCode($code, $clientId, $repositoryId = null) {

        $query = 'select id, name, code from svn_repository where client_id = ? and LOWER(code) = LOWER(?) ';

        if ($repositoryId) $query .= 'and id != ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($repositoryId) {
            $stmt->bind_param("isi", $clientId, $code, $repositoryId);
        } else {
            $stmt->bind_param("is", $clientId, $code);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function getLastMinute() {

        $query = 'SELECT svn_repository.id
                    FROM svn_repository
                    WHERE date_created BETWEEN (DATE_SUB(NOW(), INTERVAL 5 MINUTE)) AND NOW()';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $resultArray = array();
            while ($record = $result->fetch_array(MYSQLI_ASSOC)) {
                $resultArray[] = $record;
            }

            return $resultArray;
        } else
            return null;
    }

    public function getById($repoId) {
        $query = 'SELECT svn_repository.*,
                         general_user.first_name, general_user.last_name
                    FROM svn_repository
                    LEFT join general_user on general_user.id = svn_repository.user_created_id
                    WHERE svn_repository.id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $repoId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getUserById($repoUserId) {
        $query = 'SELECT svn_repository_user.*,
                         general_user.first_name, general_user.last_name, general_user.email, general_user.username
                    FROM svn_repository_user
                    LEFT join general_user on general_user.id = svn_repository_user.user_id
                    WHERE general_user.id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $repoUserId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getUserByRepoIdAndUserId($repoId, $userId) {
        $query = 'SELECT svn_repository_user.*,
                         general_user.first_name, general_user.last_name, general_user.email, general_user.username
                    FROM svn_repository_user
                    LEFT join general_user on general_user.id = svn_repository_user.user_id
                    WHERE svn_repository_user.svn_repository_id = ? and svn_repository_user.user_id = ?
                    limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $repoId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function addRepo($clientId, $userCreatedId, $name, $description, $code, $currentDate) {
        $query = "INSERT INTO svn_repository(client_id, user_created_id, name, description, code, date_created) VALUES " .
            "(?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iissss", $clientId, $userCreatedId, $name, $description, $code, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function updateRepo($description, $code, $repoId, $date) {
        $query = "UPDATE svn_repository SET
                    description = ?,
                    code = ?,
                    date_updated = ?
                  WHERE svn_repository.id = ?
                  LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $description, $code, $date, $repoId);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getAllByClientId($clientId, $resultType = null, $resultColumn = null) {
        $query = 'SELECT svn_repository.id, svn_repository.client_id, user_created_id, name, description, code,
                            svn_repository.date_created, general_user.first_name, general_user.last_name ' .
                    'FROM svn_repository ' .
                    'LEFT join general_user ON svn_repository.user_created_id = general_user.id ' .
                    'WHERE svn_repository.client_id = ? ' .
                    'ORDER BY svn_repository.id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($record = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn)
                        $resultArray[] = $record[$resultColumn];
                    else
                        $resultArray[] = $record;
                }

                return $resultArray;
            } else return $result;
        } else
            return null;
    }

    public function getAll($filters = array()) {
        $query = 'SELECT svn_repository.id, svn_repository.client_id, user_created_id, name, description, code,
                            svn_repository.date_created, general_user.first_name, general_user.last_name,
                            client.company_domain ' .
                    'FROM svn_repository ' .
                    'LEFT join general_user ON svn_repository.user_created_id = general_user.id ' .
                    'LEFT JOIN client ON client.id = svn_repository.client_id ' .
                    'WHERE 1 = 1 ';

        if (!empty($filters['today'])) {
            $query .= " AND DATE(svn_repository.date_created) = DATE(NOW())";
        }

        if (!empty($filters['sort_by'])) {
            $query .= " ORDER BY " . $filters['sort_by'] . ' ' . $filters['sort_order'];
        }
        else {
            $query .= " ORDER BY svn_repository.id";
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function getRepositoriesByUserId($clientId, $userId, $resultType = null, $resultColumn = null) {
        $query = 'SELECT svn_repository.id, svn_repository.client_id, user_created_id, name, description, code,
                            svn_repository.date_created, general_user.first_name, general_user.last_name ' .
                    'FROM svn_repository ' .
                    'LEFT join general_user ON svn_repository.user_created_id = general_user.id ' .
                    'LEFT JOIN svn_repository_user ON svn_repository_user.svn_repository_id = svn_repository.id ' .
                    'WHERE svn_repository.client_id = ? ' .
                    'and svn_repository_user.user_id = ? ' .
                    'ORDER BY svn_repository.id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $clientId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($record = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn)
                        $resultArray[] = $record[$resultColumn];
                    else
                        $resultArray[] = $record;
                }

                return $resultArray;
            } else return $result;
        } else
            return null;
    }

    public function getUserList($repoId, $resultType = null, $resultColumn = null) {
        $query = 'SELECT svn_repository_user.*,
                         general_user.first_name, general_user.last_name, general_user.email, general_user.username
                    FROM svn_repository_user
                    LEFT join general_user on general_user.id = svn_repository_user.user_id
                    WHERE svn_repository_user.svn_repository_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $repoId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($record = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn)
                        $resultArray[] = $record[$resultColumn];
                    else
                        $resultArray[] = $record;
                }

                return $resultArray;
            } else return $result;
        } else
            return null;
    }

    public function addUser($repoId, $userId) {
        $query = "INSERT INTO svn_repository_user(svn_repository_id, user_id, date_created) " .
                        "VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $currentTime = time();
        $stmt->bind_param("iis", $repoId, $userId, $currentTime);
        $stmt->execute();
    }

    public function updateUserPermissions($repoId, $userId, $hasRead, $hasWrite) {
        $query = "UPDATE svn_repository_user
                    SET has_read = ?,
                        has_write = ?
                    WHERE svn_repository_id = ?
                      AND user_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiii", $hasRead, $hasWrite, $repoId, $userId);
        $stmt->execute();
    }

    public function updateUserPassword($repoId, $userId, $password) {
        $query = "UPDATE svn_repository_user
                    SET password = ?
                    WHERE svn_repository_id = ?
                      AND user_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $password = SVNCrypt::crypt($password);
        $stmt->bind_param("sii", $password, $repoId, $userId);
        $stmt->execute();
    }

    public function deleteById($Id) {
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        $query = "DELETE IGNORE FROM svn_repository_user WHERE svn_repository_id = " . $Id;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = "DELETE IGNORE FROM svn_repository WHERE id = " . $Id . ' limit 1';
        UbirimiContainer::get()['db.connection']->query($query);

        $query = "SET FOREIGN_KEY_CHECKS = 1;";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteAllById($Id) {
        $repo = UbirimiContainer::get()['repository']->get(SvnRepository::class)->getById($Id);
        $client = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getById($repo['svn_repository.client_id']);

        self::deleteById($Id);

        /* update apache configs */
        SvnRepository::updateHtpasswd($repo['id'], $client['company_domain']);
        SvnRepository::updateAuthz();

        /* delete from the disk */
        $path = UbirimiContainer::get()['subversion.path'] . Util::slugify($client['company_domain']) . '/' . Util::slugify($repo['name']);
        system("rm -rf $path");

        /* refresh apache config */
        SvnRepository::refreshApacheConfig();
    }

    public function deleteUserById($Id) {
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        $query = "DELETE IGNORE FROM svn_repository_user WHERE user_id = " . (int) $Id;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = "SET FOREIGN_KEY_CHECKS = 1;";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    /**
     * Create SVN repository with standard organisation
     * /trunk
     * /tags
     * /branches
     *
     * @param string Path to create subversion
     */
    public function createSvn($path) {
        $escape_path = escapeshellarg($path);
        $message = ConsoleUtils::runCmdCaptureMessage(SVNUtils::svnadminCommand("create $escape_path"), $return);
        if ($return) {
            throw new Exception("Can't create subversion repository: " . $message);
        }
    }

    public function updateHtpasswd($repoId, $companyDomain) {
        $text = "";

        $repository = UbirimiContainer::get()['repository']->get(SvnRepository::class)->getById($repoId);

        $query = "select general_user.username, svn_repository_user.password
                    FROM svn_repository_user
                    LEFT join general_user on general_user.id = svn_repository_user.user_id
                    WHERE svn_repository_user.svn_repository_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $repoId);

        $stmt->execute();
        $result = $stmt->get_result();

        while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
            if (!empty($user['password']) && !empty($user['username'])) {
                $text .= $user['username'] . ':' . $user['password'] . "\n";
            }
        }

        $path = str_replace('REPO_DIR', Util::slugify($repository['name']), UbirimiContainer::get()['subversion.passwd']);
        $path = str_replace('CLIENT_DIR', Util::slugify($companyDomain), $path);
        @file_put_contents($path, $text);
    }

    public function updateAuthz() {
        $text = "# This is an auto generated file! Edit at your own risk!\n";
        $text .= "# You can edit this \"/\" section. Settings will be kept\n";
        $text .= "#\n";
        $text .= "[/]\n";
        $text .= "* = \n";
        $text .= "\n";

        $query = "SELECT * FROM svn_repository";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($repository = $result->fetch_array(MYSQLI_ASSOC)) {
            $text_svn_projects = '';
            $text_svn_groups = "[groups]\n";
            $repo_read_users = "";
            $repo_read_write_users = "";

            $query2 = "SELECT *
                            FROM svn_repository_user
                            LEFT join general_user on general_user.id = svn_repository_user.user_id
                            WHERE svn_repository_id = ?";

            $stmt2 = UbirimiContainer::get()['db.connection']->prepare($query2);
            $stmt2->bind_param("i", $repository['id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            while ($svn_user = $result2->fetch_array(MYSQLI_ASSOC)) {
                if (!empty($svn_user['password']) && !empty($svn_user['username'])) {
                    if (1 == $svn_user['has_read'] && 1 == $svn_user['has_write']) {
                        $repo_read_write_users .= $svn_user['username'] . ',';
                    }
                    else if (1 == $svn_user['has_read']) {
                        $repo_read_users .= $svn_user['username'] . ',';
                    }
                }
            }

            if (!empty($repo_read_users)) {
                $repo_read_users = substr($repo_read_users, 0, -1);
                $text_svn_groups .= "group_" . Util::slugify($repository['name']) . '_read = ' . $repo_read_users . "\n";
            }

            if (!empty($repo_read_write_users)) {
                $repo_read_write_users = substr($repo_read_write_users, 0, -1);
                $text_svn_groups .= "group_" . Util::slugify($repository['name']) . '_read_write = ' . $repo_read_write_users . "\n";
            }

            if (!empty($repo_read_users) || !empty($repo_read_write_users)) {
                $text_svn_projects .= "[" . Util::slugify($repository['name']) . ":/]\n";

                if (!empty($repo_read_users)) {
                    $text_svn_projects .= "@group_" . Util::slugify($repository['name']) . "_read = r\n";
                }

                if (!empty($repo_read_write_users)) {
                    $text_svn_projects .= "@group_" . Util::slugify($repository['name']) . "_read_write = rw\n";
                }
            }

            $clientData = UbirimiContainer::get()['session']->get('client');

            $path = str_replace('REPO_DIR', Util::slugify($repository['name']), UbirimiContainer::get()['subversion.authz']);
            $path = str_replace('CLIENT_DIR', Util::slugify($clientData['company_domain']), $path);

            @file_put_contents($path, $text . $text_svn_groups . "\n" . $text_svn_projects);
        }
    }

    public function apacheConfig($clientDomain, $repositoryName) {
        file_put_contents(UbirimiContainer::get()['subversion.apache_config'], "Use SubversionRepo $clientDomain $repositoryName\n", FILE_APPEND | LOCK_EX);
    }

    public function refreshApacheConfig() {
        $text = "";

        $query = "SELECT *
                      FROM svn_repository
                      LEFT JOIN client on svn_repository.client_id = client.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        while ($repo = $result->fetch_array(MYSQLI_ASSOC)) {
            $text .= "Use SubversionRepo " . Util::slugify($repo['company_domain']) . ' ' . Util::slugify($repo['name']) . "\n";
        }

        file_put_contents(UbirimiContainer::get()['subversion.apache_config'], $text, LOCK_EX);
    }

    public function getAdministratorsByClientId($clientId) {
        $query = 'select general_user.* from general_user WHERE client_id = ? and svn_administrator_flag = 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addAdministrator($users) {
        $query = "update general_user set svn_administrator_flag = 1 where id IN (" . implode(', ', $users) . ")";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
    }

    public function deleteAdministratorById($clientId, $Id) {
        $query = "update general_user set svn_administrator_flag = 0 where client_id = ? and id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $Id);
        $stmt->execute();
    }
}
