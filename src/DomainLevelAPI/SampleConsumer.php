<?php

use ILIAS\Course\ilCreateCourseMembershipCommand;
use ILIAS\DomainLevelAPI\ilCommandBus;

class SampleConsumer
{
    public function callCreateMembership()
    {
        global $DIC;

        $command_bus = new ilCommandBus(
            new \ILIAS\DomainLevelAPI\ilCommandHandlerLocator(
                new ILIAS\DomainLevelAPI\ilCommandHandlerMap()
            )
        );
        // or $DIC->api ...

        $command = new ilCreateCourseMembershipCommand(
            4711,
            1234,
            ilCourseParticipant::MEMBERSHIP_MEMBER,
             $DIC->user()->getId()
            );

        $result = $command_bus->dispatch($command);

    }
}