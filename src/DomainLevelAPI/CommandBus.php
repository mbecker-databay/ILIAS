<?php

namespace ILIAS\DomainLevelAPI;

interface CommandBus
{
    /**
     * @param Command $command
     *
     * @return CommandResult
     */
    public function dispatch(Command $command) : CommandResult;
}