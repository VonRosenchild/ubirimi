<?php

    namespace ubirimi\svn;

    use Exception;
    use Ubirimi\ConsoleUtils;
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    class SVNRepository {

        public static function getByCode($code, $clientId) {
            return false;

            $query = 'select id, name, code from project where client_id = ? and LOWER(code) = LOWER(?) ';
            if ($projectId) $query .= 'and id != ?';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                if ($projectId) $stmt->bind_param("isi", $clientId, $code, $projectId);
                else
                    $stmt->bind_param("is", $clientId, $code);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows)
                    return $result;
                else
                    return false;
            }
        }

        public static function getById($repoId) {
            $query = 'SELECT svn_repository.*,
                             user.first_name, user.last_name
                        FROM svn_repository
                        LEFT JOIN user ON user.id = svn_repository.user_created_id
                        WHERE svn_repository.id = ? ';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->bind_param("i", $repoId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows)
                    return $result->fetch_array(MYSQLI_ASSOC);
                else
                    return null;
            }
        }

        public static function getUserById($repoUserId) {
            $query = 'SELECT svn_repository_user.*,
                             user.first_name, user.last_name, user.email, user.username
                        FROM svn_repository_user
                        LEFT JOIN user ON user.id = svn_repository_user.user_id
                        WHERE user.id = ? ';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->bind_param("i", $repoUserId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows)
                    return $result->fetch_array(MYSQLI_ASSOC);
                else
                    return null;
            }
        }

        public static function getUserByRepoIdAndUserId($repoId, $userId) {
            $query = 'SELECT svn_repository_user.*,
                             user.first_name, user.last_name, user.email, user.username
                        FROM svn_repository_user
                        LEFT JOIN user ON user.id = svn_repository_user.user_id
                        WHERE svn_repository_user.svn_repository_id = ? and svn_repository_user.user_id = ?
                        limit 1';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->bind_param("ii", $repoId, $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows)
                    return $result->fetch_array(MYSQLI_ASSOC);
                else
                    return null;
            }
        }

        public static function addRepo($clientId, $userCreatedId, $name, $description, $code, $currentDate) {
            $query = "INSERT INTO svn_repository(client_id, user_created_id, name, description, code, date_created) VALUES " .
                "(?, ?, ?, ?, ?, ?)";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("iissss", $clientId, $userCreatedId, $name, $description, $code, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;
            }
        }

        public static function updateRepo($description, $code, $repoId, $date) {
            $query = "UPDATE svn_repository SET
                        description = ?,
                        code = ?,
                        date_updated = ?
                      WHERE svn_repository.id = ?
                      LIMIT 1";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("sssi", $description, $code, $date, $repoId);
                $stmt->execute();
                return UbirimiContainer::get()['db.connection']->insert_id;
            }
        }

        public static function getAllByClientId($clientId, $resultType = null, $resultColumn = null) {
            $query = 'SELECT svn_repository.id, svn_repository.client_id, user_created_id, name, description, code,
                                svn_repository.date_created, user.first_name, user.last_name ' .
                        'FROM svn_repository ' .
                        'LEFT JOIN user ON svn_repository.user_created_id = user.id ' .
                        'WHERE svn_repository.client_id = ? ' .
                        'ORDER BY svn_repository.id';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

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
        }

        public static function getAll($filters = array()) {
            $query = 'SELECT svn_repository.id, svn_repository.client_id, user_created_id, name, description, code,
                                svn_repository.date_created, user.first_name, user.last_name,
                                client.company_domain ' .
                        'FROM svn_repository ' .
                        'LEFT JOIN user ON svn_repository.user_created_id = user.id ' .
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

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows) {
                    return $result;
                } else
                    return null;
            }
        }

        public static function getRepositoriesByUserId($clientId, $userId, $resultType = null, $resultColumn = null) {
            $query = 'SELECT svn_repository.id, svn_repository.client_id, user_created_id, name, description, code,
                                svn_repository.date_created, user.first_name, user.last_name ' .
                        'FROM svn_repository ' .
                        'LEFT JOIN user ON svn_repository.user_created_id = user.id ' .
                        'LEFT JOIN svn_repository_user ON svn_repository_user.svn_repository_id = svn_repository.id ' .
                        'WHERE svn_repository.client_id = ? ' .
                        'and svn_repository_user.user_id = ? ' .
                        'ORDER BY svn_repository.id';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

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
        }

        public static function getUserList($repoId, $resultType = null, $resultColumn = null) {
            $query = 'SELECT svn_repository_user.*,
                             user.first_name, user.last_name, user.email, user.username
                        FROM svn_repository_user
                        LEFT JOIN user ON user.id = svn_repository_user.user_id
                        WHERE svn_repository_user.svn_repository_id = ? ';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

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
        }

        public static function addUser($repoId, $userId) {
            $query = "INSERT INTO svn_repository_user(svn_repository_id, user_id, date_created) " .
                            "VALUES (?, ?, ?)";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $currentTime = time();
                $stmt->bind_param("iis", $repoId, $userId, $currentTime);
                $stmt->execute();
            }
        }

        public static function updateUserPermissions($repoId, $userId, $hasRead, $hasWrite) {
            $query = "UPDATE svn_repository_user
                        SET has_read = ?,
                            has_write = ?
                        WHERE svn_repository_id = ?
                          AND user_id = ?";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("iiii", $hasRead, $hasWrite, $repoId, $userId);
                $stmt->execute();
            }
        }

        public static function updateUserPassword($repoId, $userId, $password) {
            $query = "UPDATE svn_repository_user
                        SET password = ?
                        WHERE svn_repository_id = ?
                          AND user_id = ?";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $password = SVNCrypt::crypt($password);
                $stmt->bind_param("sii", $password, $repoId, $userId);
                $stmt->execute();
            }
        }

        public static function deleteById($Id) {
            $query = "SET FOREIGN_KEY_CHECKS = 0;";
            UbirimiContainer::get()['db.connection']->query($query);

            $query = "DELETE IGNORE FROM svn_repository_user WHERE svn_repository_id = " . $Id;
            UbirimiContainer::get()['db.connection']->query($query);

            $query = "DELETE IGNORE FROM svn_repository WHERE id = " . $Id . ' limit 1';
            UbirimiContainer::get()['db.connection']->query($query);

            $query = "SET FOREIGN_KEY_CHECKS = 1;";
            UbirimiContainer::get()['db.connection']->query($query);
        }

        public static function deleteAllById($Id) {
            $repo = SVNRepository::getById($Id);
            $client = Client::getById($repo['svn_repository.client_id']);

            self::deleteById($Id);

            /* update apache configs */
            SVNRepository::updateHtpasswd($repo['id'], $client['company_domain']);
            SVNRepository::updateAuthz();

            /* delete from the disk */
            $path = UbirimiContainer::get()['subversion.path'] . Util::slugify($client['company_domain']) . '/' . Util::slugify($repo['name']);
            system("rm -rf $path");

            /* refresh apache config */
            SVNRepository::refreshApacheConfig();
        }

        public static function deleteUserById($Id) {
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
        public static function createSvn($path) {
            $escape_path = escapeshellarg($path);
            $message = ConsoleUtils::runCmdCaptureMessage(SVNUtils::svnadminCommand("create $escape_path"), $return);
            if ($return) {
                throw new Exception("Can't create subversion repository: " . $message);
            }
        }

        public static function updateHtpasswd($repoId, $companyDomain) {
            $text = "";

            $repository = SVNRepository::getById($repoId);

            $query = "SELECT user.username, svn_repository_user.password
                        FROM svn_repository_user
                        LEFT JOIN user on user.id = svn_repository_user.user_id
                        WHERE svn_repository_user.svn_repository_id = ?";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $repoId);

                $stmt->execute();
                $result = $stmt->get_result();

                while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
                    if (!empty($user['password']) && !empty($user['username'])) {
                        $text .= $user['username'] . ':' . $user['password'] . "\n";
                    }
                }
            }
            else {

            }

            $path = str_replace('REPO_DIR', Util::slugify($repository['name']), UbirimiContainer::get()['subversion.passwd']);
            $path = str_replace('CLIENT_DIR', Util::slugify($companyDomain), $path);
            @file_put_contents($path, $text);
        }

        public static function updateAuthz() {
            $text = "# This is an auto generated file! Edit at your own risk!\n";
            $text .= "# You can edit this \"/\" section. Settings will be kept\n";
            $text .= "#\n";
            $text .= "[/]\n";
            $text .= "* = \n";
            $text .= "\n";

            $query = "SELECT * FROM svn_repository";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->execute();
                $result = $stmt->get_result();

                while ($repository = $result->fetch_array(MYSQLI_ASSOC)) {
                    $text_svn_projects = '';
                    $text_svn_groups = "[groups]\n";
                    $repo_read_users = "";
                    $repo_read_write_users = "";

                    $query2 = "SELECT *
                                    FROM svn_repository_user
                                    LEFT JOIN user on user.id = svn_repository_user.user_id
                                    WHERE svn_repository_id = ?";

                    if ($stmt2 = UbirimiContainer::get()['db.connection']->prepare($query2)) {
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
                    }

                    $clientData = UbirimiContainer::get()['session']->get('client');

                    $path = str_replace('REPO_DIR', Util::slugify($repository['name']), UbirimiContainer::get()['subversion.authz']);
                    $path = str_replace('CLIENT_DIR', Util::slugify($clientData['company_domain']), $path);

                    @file_put_contents($path, $text . $text_svn_groups . "\n" . $text_svn_projects);
                }
            }
        }

        public static function apacheConfig($clientDomain, $repositoryName) {
            file_put_contents(UbirimiContainer::get()['subversion.apache_config'], "Use SubversionRepo $clientDomain $repositoryName\n", FILE_APPEND | LOCK_EX);
            system("/etc/init.d/apache2 reload");
        }

        public static function refreshApacheConfig() {
            $text = "";

            $query = "SELECT *
                          FROM svn_repository
                          LEFT JOIN client on svn_repository.client_id = client.id";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->execute();
                $result = $stmt->get_result();

                while ($repo = $result->fetch_array(MYSQLI_ASSOC)) {
                    $text .= "Use SubversionRepo " . Util::slugify($repo['company_domain']) . ' ' . Util::slugify($repo['name']) . "\n";
                }
            }

            file_put_contents(UbirimiContainer::get()['subversion.apache_config'], $text, LOCK_EX);
            system("/etc/init.d/apache2 reload");
        }

        public static function getAdministratorsByClientId($clientId) {
            $query = 'SELECT user.* FROM user WHERE client_id = ? and svn_administrator_flag = 1';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->bind_param("i", $clientId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows)
                    return $result;
                else
                    return null;
            }
        }

        public static function addAdministrator($users) {
            $query = "UPDATE user SET svn_administrator_flag = 1 where id IN (" . implode(', ', $users) . ")";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->execute();
            }
        }

        public static function deleteAdministratorById($clientId, $Id) {
            $query = "UPDATE user SET svn_administrator_flag = 0 where client_id = ? and id = ? limit 1";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("ii", $clientId, $Id);
                $stmt->execute();
            }
        }
    }