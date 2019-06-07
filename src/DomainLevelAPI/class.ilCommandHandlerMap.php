<?php


namespace ILIAS\DomainLevelAPI;


class ilCommandHandlerMap implements CommandHandlerMap
{
    protected $commandHandlerMap = array(
        'ilCreateCourseMembership' => 'ilCreateCourseMembershipHandler'
    );

    /**
     * @param string $command
     * @return string
     */
    public function getCommandHandler($command) : string
    {
        return $this->commandHandlerMap[get_class($command)];
    }
}