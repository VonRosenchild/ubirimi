<?php

namespace Ubirimi\SVNHosting\Event;

use Symfony\Component\EventDispatcher\Event;

class SVNHostingEvent extends Event
{
    /**
     * Slugged name of SVN repo
     *
     * @var string
     */
    private $name;

    /**
     * The user that performed the action
     *
     * @var array
     */
    private $user;

    /**
     * Extra data passed to the event
     *
     * @var array
     */
    private $extra;

    public function __construct($name = null, $user = null, $extra = array())
    {
        $this->name = $name;
        $this->user = $user;
        $this->extra = $extra;
    }

    /**
     * Get name
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get user
     *
     * @return array|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get extra data
     *
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }
}