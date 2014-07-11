<?php

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Container\UbirimiContainer;

class ProjectVersion {

    public static function getByIds($Ids) {
        $query = 'SELECT project_version.* ' .
            'FROM project_version ' .
            'WHERE id IN (' . implode(', ', $Ids) . ') ';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function deleteByProjectId($projectId)
    {
        $versions = Project::getVersions($projectId);

        while ($version = $versions->fetch_array(MYSQLI_ASSOC)) {
            $versionId = $version['id'];
            ProjectVersion::deleteById($versionId);

        }
    }

    public static function deleteVById($versionId)
    {
        $query = 'delete from issue_version where project_version_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $versionId);
            $stmt->execute();
        }

        $query = 'delete from project_version where id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $versionId);
            $stmt->execute();
        }
    }
}