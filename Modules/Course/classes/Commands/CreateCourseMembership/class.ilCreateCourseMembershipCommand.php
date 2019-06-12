<?php

namespace ILIAS\Course;

use http\Exception\InvalidArgumentException;
use ILIAS\DomainLevelAPI\Command;
use ilObject;
use ilObjUser;

/**
 * Class ilCreateCourseMembershipCommand
 * @package ILIAS\Course
 */
class ilCreateCourseMembershipCommand implements Command
{
    /** @var int $user_obj_id */
    protected $user_obj_id;

    /** @var int $course_obj_id */
    protected $course_obj_id;

    /** @var int $local_role_id */
    protected $local_role_id;

    /** @var int $actor_user_obj_id */
    protected $actor_user_obj_id;

    /**
     * ilCreateCourseMembershipCommand constructor.
     *
     * @param integer $user_obj_id       User ID of the user to be assigned
     * @param integer $course_obj_id     Course Object ID of the course the user is to be assigned to
     * @param integer $local_role_id     Role ID of the new user-course-relation
     * @param integer $actor_user_obj_id User ID of the acting user
     *
     */
    public function __construct(int $user_obj_id, int $course_obj_id, int $local_role_id, int $actor_user_obj_id)
    {
        global $DIC;
        if(ilObject::_exists($DIC->refinery()->to()->int()->transform($course_obj_id)))
        {
            $this->course_obj_id        = $course_obj_id;
        } else {
            $DIC->logger()->root()->warning(__CLASS__ . ': Course Object Id is invalid');
            throw new InvalidArgumentException(__CLASS__ . ': Course Object Id is invalid');
        }

        if(ilObjUser::_exists($DIC->refinery()->to()->int()->transform($user_obj_id))) {
            $this->user_obj_id          = $user_obj_id;
        } else {
            $DIC->logger()->root()->warning(__CLASS__ . ': User Object Id is invalid');
            throw new InvalidArgumentException(__CLASS__ . ': User Object Id is invalid');
        }

        if(in_array($DIC->refinery()->to()->int()->transform($local_role_id), array(1,2,3))) {
            $this->local_role_id        = $local_role_id;
        } else {
            $DIC->logger()->root()->warning(__CLASS__ . ': Local Role is invalid');
            throw new InvalidArgumentException(__CLASS__ . ': Local Role is invalid');
        }

        if($DIC->rbac()->system()->checkAccessOfUser(
            $DIC->refinery()->to()->int()->transform($actor_user_obj_id),
            'write',
            current(ilObject::_getAllReferences($this->course_obj_id))
            )
        ) {
            $this->actor_user_obj_id = $actor_user_obj_id;
        } else {
            $DIC->logger()->root()->warning(__CLASS__ . ': No permission for user');
            throw new InvalidArgumentException(__CLASS__ . ': No permission for user');
        }
        $DIC->logger()->root()->debug('Command object ' . __CLASS__ . ' instantiated');
    }

    /**
     * @return int
     */
    public function getUserObjId() : int
    {
        return $this->user_obj_id;
    }

    /**
     * @return int
     */
    public function getCourseObjId() : int
    {
        return $this->course_obj_id;
    }

    /**
     * @return int
     */
    public function getLocalRoleId() : int
    {
        return $this->local_role_id;
    }

    /**
     * @return int
     */
    public function getActorUserObjId() : int
    {
        return $this->actor_user_obj_id;
    }


}