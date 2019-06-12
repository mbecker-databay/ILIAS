<?php

namespace ILIAS\Course;

use ilCourseParticipants;
use ilCreateCourseMembershipResult;
use ILIAS\DomainLevelAPI\Command;
use ILIAS\DomainLevelAPI\CommandHandler;
use ILIAS\DomainLevelAPI\CommandResult;

class ilCreateCourseMembershipHandler implements CommandHandler
{

    /**
     * @param Command $command
     * @return ilCreateCourseMembershipResult
     */
    public function handle(Command $command) : CommandResult
    {
        $participant_object = new ilCourseParticipants($command->getCourseObjId());
        $participant_object->add($command->getUserObjId(), $command->getLocalRoleId());
        return new ilCreateCourseMembershipResult();
    }
}