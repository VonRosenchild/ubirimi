<?php

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