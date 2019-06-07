<?php

namespace ILIAS\DomainLevelAPI;

class ilCommandBus implements CommandBus
{
    /**
     * @var CommandHandlerLocator $locator
     */
    protected $locator;

    public function __construct(CommandHandlerLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param Command $command
     * @return CommandResult
     */
    public function dispatch(Command $command) : CommandResult
    {
        $handler = $this->locator->getHandler($command);
        return $handler->handle($command);
    }
}