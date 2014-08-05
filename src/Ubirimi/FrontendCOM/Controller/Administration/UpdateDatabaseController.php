<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;
    use Ubirimi\Yongo\Repository\Issue\IssueHistory;

    Util::checkSuperUserIsLoggedIn();

    $currentDate = Util::getServerCurrentDateTime();

    $history = IssueHistory::getAll();

    $issues = \Ubirimi\Yongo\Repository\Issue\Issue::getAll();

    while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
        if ($issue['date_resolved'] == null) {

            // look into the history
            $query = 'select * from issue_history where issue_id = ' . $issue['id'] . " and field = 'resolution' order by id desc";

            $stmt = \Ubirimi\Container\UbirimiContainer::get()['db.connection']->prepare($query);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows) {
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($data['new_value'] && $data['new_value'] != 'NULL') {
                        \Ubirimi\Yongo\Repository\Issue\Issue::updateById($issue['id'], array('date_resolved' => $data['date_created']), $data['date_created']);
                        break;
                    } else {
                        break;
                    }
                }
            }
        }
    }