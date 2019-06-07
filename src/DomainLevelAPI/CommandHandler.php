<?php

namespace ILIAS\DomainLevelAPI;

interface CommandHandler
{
    /**
     * @param Command $command
     *
     * @return CommandResult
     */
    public function handle(Command $command) : CommandResult;
}