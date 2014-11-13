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

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Container\UbirimiContainer;

class ProjectVersion
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
        $versions = UbirimiContainer::get()['repository']->get(YongoProject::class)->getVersions($projectId);

        while ($versions && $version = $versions->fetch_array(MYSQLI_ASSOC)) {
            $versionId = $version['id'];
            UbirimiContainer::get()['repository']->get(ProjectVersion::class)->deleteById($versionId);
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
