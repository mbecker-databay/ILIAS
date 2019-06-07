<?php

namespace ILIAS\DomainLevelAPI;

use ILIAS\DomainLevelAPI\CommandHandler;

interface CommandHandlerMap
{
    /**
     * @param string $command
     * @return string
     */
    public function getCommandHandler($command) : string;
}