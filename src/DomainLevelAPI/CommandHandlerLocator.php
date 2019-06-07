<?php

namespace ILIAS\DomainLevelAPI;

use ILIAS\DomainLevelAPI\Command;

interface CommandHandlerLocator
{
    /**
     * @param Command $command
     * @return CommandHandler
     */
    public function getHandler(Command $command) : CommandHandler;
}