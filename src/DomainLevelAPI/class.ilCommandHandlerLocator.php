<?php

use ILIAS\DomainLevelAPI\Command;
use ILIAS\DomainLevelAPI\CommandHandler;
use ILIAS\DomainLevelAPI\CommandHandlerLocator;
use ILIAS\DomainLevelAPI\CommandHandlerMap;

class ilCommandHandlerLocator implements CommandHandlerLocator
{
    /**
     * @var CommandHandlerMap $map
     */
    protected $map;

    public function __construct(CommandHandlerMap $map)
    {
        $this->map = $map;
    }

    /**
     * @param Command           $command
     *
     * @return CommandHandler
     */
    public function getHandler(Command $command) : CommandHandler
    {
        global $DIC;
        $handler_class = $this->map->getCommandHandler(get_class($command));
        return new $handler_class($DIC);
    }
}