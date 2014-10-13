<?php

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Container\UbirimiContainer;

class Version
{
    public function getByIds($Ids)
    {
        $query = 'SELECT project_version.* ' .
            'FROM project_version ' .
            'WHERE id IN (' . implode(', ', $Ids) . ') ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteByProjectId($projectId)
    {
        $versions = Project::getVersions($projectId);

        while ($versions && $version = $versions->fetch_array(MYSQLI_ASSOC)) {
            $versionId = $version['id'];
            Version::deleteById($versionId);
        }
    }

    public function deleteById($versionId)
    {
        $query = 'delete from issue_version where project_version_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $versionId);
        $stmt->execute();

        $query = 'delete from project_version where id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $versionId);
        $stmt->execute();
    }
}
