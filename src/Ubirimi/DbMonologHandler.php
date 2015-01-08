<?php

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiLog;

class DbMonologHandler extends AbstractProcessingHandler
{
    private $statement;

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        UbirimiContainer::get()['repository']->get(UbirimiLog::class)->add($record['context']['client_id'], $record['context']['user_id'], $record['message']);
    }
}