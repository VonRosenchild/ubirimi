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

namespace Ubirimi\Yongo\Event;

use Symfony\Component\EventDispatcher\Event;

class IssueEvent extends Event
{
    const STATUS_NEW = 0;
    const STATUS_UPDATE = 1;
    const STATUS_DELETE = 2;

    private $issue;
    private $status;
    private $project;
    private $extra;

    public function __construct($issue, $project = null, $status = null, $extra = null) {
        $this->issue = $issue;
        $this->project = $project;
        $this->status = $status;
        $this->extra = $extra;
    }

    public function getIssue()
    {
        return $this->issue;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getExtra()
    {
        return $this->extra;
    }
}