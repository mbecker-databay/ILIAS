This document is currently work in progress. 

# Internal Aligned API

## Definitions

Sticks and stones may break my bones
... and words can do quite some harm. Let's dive into definitions for a moment so we all speak the same language here.

### "Internal"
Before anything else, it should be pointed out how to understand the title, especially the word "internal". An API sits as controlled gateway to functionality on a boundary, managing access to functions. Some argue that a public method on an object you get your hands on may already be considered a an API. This will not be followed here. The Internal Aligned API is internal in its way to be not offering an outside facing, external interface. It acts agnostic on the question by what it is used, how the consumers of the API are driven.
The first logical step forward is thinking about how it is used. The usage pattern is simple, a key design goal, and safe, too, but that doesn't make it a particularly good choice for many intentions in ILIAS. So let's add some new terms and definitions. As you know, the ILIAS community is really hilariously *bad* at picking names (with yours truly being lord-master of bad name pickers, not denying that) and if we do, they sound cool and well known, but eventually mean something very creative and individual. Keep in mind, the ancestor concept of this API was titled "Service Discovery" and while there were services you might discover, it was not anything like the technical concept the rest of the information technology world means when they say that.
 
### "Domain Level"
The first new one we add in here is "Domain Level". We understand a common set of abstract user interactions with the user interface as domain level actions. One possible domain level action is the "Creation of a Course Object". We do neither go into the detail how that is operated ("The user clicks on new, adds a title on the next screen, confirms") nor do we take the full complexity of every possible configuation option into the creation, as this is done in subsequent steps "Changing Course Options".
When speaking of "Domain Level", it is worth pointing out that we have "the" domain level. At first, we like to think about what dwells in the modules-folder, objects we can add to our repository. For them, it's very true, things you or your users do with them, are domain level activities. It may well be that some abstract concepts hide in components we did not yet bring fully out to the users sights, that form commands or queries which are part of the domain level. Querying a list of a users memberships would lead to the assumption that "Membership" has a domain level in the sense we speak about.
Still, not every subsystem or service that has a purpose to fulfill, a reason to exist and over that provides valuable functionality does shine through at the domain level. A PDF generation service is certainly a most wonderful thing to have and many implementations might take advantage of it, like "Get Student Test Result PDF", but the service itself does not appear in the domain level as we understand it here.
The API is not geared towards "vertical servicing", speaking with components on much different levels of abstraction. It is exactly not the API that helps your component Wiki to integrate functionality of mail for its own purposes. No, you finding *some* parts that may look or smell like that could be possible all good and fine but the design of the API doesn't make it a good option. This is due to the API doing some pricey security checks on every commands instantiation: It will kill your performance swiftly if you're going down that road.

### "CQRS - Command Query Responsibility Segregation"
The next term we bring in is "CQRS - Command Query Responsibility Segregation". We will use it as it was meant. What we won't use is the whole set of engineering that comes with it in the vanilla descriptions and courses. We see this happening from time to time and maybe you remember the times well when the MVC was perverted by some Ruby developers to become an architectural paradigm and everyone put habitually business logic into the controllers and the model was in fact the persistence layer. In the same way, we see today Event Sourcing, Event Store, Snapshot Store, Persistent Projections, Service Bus and Message Queue, a whole bouquet of design-chichi in tutorials. Things we probably don't (all) need in general - and not for the Internal Aligned API in particular.
For the purpose of this document, we follow back to the ancestor of CQRS, the CQS, the Command Query Separation. "Asking the questions must not change the answer". This leads to our Aligned API having
two fundamentally different things: Commands and Queries. Queries do not change the state of the application, Commands do not deliver data. Still, don't be fooled: They may well do deliver information like confirming the successful execution of a command as well as error/exception information. Clever and informative, yes, just no data.  
Building on top of that, CQRS means a little more. It is meant as principle to free the developers from constraints of their own frameworks and designs to separate the the process of querying system state from manipulating it. Queries may follow radical "performance first" paradigms where commands are executed in a "security and system-integrity first" thinking.
It is understandable how Event Sourcing and the gang got into this and there is much benefit for these approaches to be applied at a larger scale for a system, including and not limited to taking advantage of microservices and allowing for stellar scalability. But this only comes on top of what we do with our API. We make way for such optimizations to happen by decoupling components and passing around requests and responses, but the actual segregation into different execution paths must be left in the hands of the component that's called. Here is no opposition to using Event Stores and all the other fine esés, it's just not the Internal APIs mission. We achieve CQRS by much simpler means than a fully blown up enterprise architecture. We're just a tiny but capable API.

## Using the API

Let's jump right in, shall we? Using the API is a dead simple task.
It's a two step task. At first you create a request object, which is a command or query object that, in both cases, encapsulates everything the commandhandler needs to know: All parameters and the user under which the command or query is to be performed. This request object is then handed to the APIs command bus and you get a response object back. Here's some code:

```php
<?php
	/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */
    	chdir("../../..");
    	require_once("Services/Init/classes/class.ilInitialisation.php");
    	ilInitialisation::initILIAS();

    	global $DIC;
    	$command = new ilCreateCourseMembershipCommand(
            7, 
            array(100, 200),
            ilCourseParticipant::MEMBERSHIP_MEMBER,
            $DIC->user()->getId()
        );
    	try {
    		$response = $DIC->api->dispatch($command);
		if ($response->isOK())
		{
			ilUtil::sendHappy($DIC['lng']->txt('stuff_worked'), true);
		}
    	}
    	catch (Exception $e) 
    	{
        	echo "Oh no, an exception. Let's see what went wrong: ".$e->getMessage();
    	}
	
?>
```

The request object is a value-object. Security and sanity are checked during the request objects dispatch. 
The request object then goes into the command bus and hands a response-object back. This may be as little as a "went well"-messenger for commands or a whole lot of data coming in it from queries. This process can deliver meaningful information to the consuming code why things didn't work out via exceptions.

And this, dear consumers, is all you need to know. You pick your command or query, instantiate it with the parameters needed and you get a response back once you hand it to dispatch.

You may, however, also do security checks and validation while the process is still in your hands. To do so, you can approach it like this:

```php
<?php
    	/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */
    	chdir("../../..");
    	require_once("Services/Init/classes/class.ilInitialisation.php");
    	ilInitialisation::initILIAS();

    	global $DIC;
    	$command = new ilCreateCourseMembershipCommand(
    		7, 
            	array(100, 200),
            	ilCourseParticipant::MEMBERSHIP_MEMBER,
            	$DIC->user()->getId()
    	);
	$validation_result = $DIC->api->validate($command);
	if($validation_result->isExecutable()) {
		try {
	 		$response = $DIC->api->dispatch($command);
		}
    		catch (Exception $e) {
        		echo "Oh no, an exception. Let's see what went wrong: ".$e->getMessage();
    		}
	
		if ($response->isOK())
		{
			ilUtil::sendHappy($DIC['lng']->txt('stuff_worked'), true);
		}
	}
?>
```

Here is the brief version of your "rules of engagement" for the consumer side:

1. You MAY check your request object prior to dispatching it to gather insight if it is executable.
2. You MUST hand your completed request object to the API as soon as possible. 
3. You SHOULD catch exceptions that may appear during the dispatching and execution of an instance of the request objects.
4. You SHOULD check if your request was worked on properly using "$response->isOK()"

### Documentation

And how do you get to the point learning the arcane magic words "ilCreateCourseMembershipCommand" and the latin formula to call it successfully? With the integrated documentation.
You will find a detailed manual on each command or query directly in your ILIAS installation.

## Making the API

Phew, so using it is really easy. But worry not, we didn't just dump all the complexity on you developers. Making your domain level actions and data available is not hard, either. We keep this intentionally simple so you have very little hinderance to make much of your API available. We even violate some coding best practices for you! We're heavily committed.

You have a little more to do than your future consumers, still, but that's not a surprise. It's three classes at the minimum for each command or query.
The first class you create is the request object, the other one is the command handler, who handles said request, which is a command or query. Finally, you want to return a response object.

## The Request Object

As stated above, the request objects are value objects. This means that we must make sure they are always valid and legit by running them through a security layer.

Let's have a look at this one:

```php
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
    /** @var int[] $user_obj_id */
    protected $user_obj_ids;

    /** @var int $course_obj_id */
    protected $course_obj_id;

    /** @var int $local_role_id */
    protected $local_role_id;

    /** @var int $actor_user_obj_id */
    protected $actor_user_obj_id;

    /**
     * ilCreateCourseMembershipCommand constructor.
     *
     * @param int[]   $user_obj_ids      List of User IDs of the users to be assigned
     * @param integer $course_obj_id     Course Object ID of the course the user is to be assigned to
     * @param integer $local_role_id     Role ID of the new user-course-relation
     * @param integer $actor_user_obj_id User ID of the acting user
     *
     */
    public function __construct(array $user_obj_id, int $course_obj_id, int $local_role_id, int $actor_user_obj_id)
    {
        global $DIC;
        $DIC->logger()->root()->debug('Command object ' . __CLASS__ . ' instantiated');
    }

	// Omitting getters here.
}
```

Now for the security / validation layer, this is an example:

```php
namespace ILIAS\Course;

use http\Exception\InvalidArgumentException;
use ILIAS\DomainLevelAPI\Validation;
use ilObject;
use ilObjUser;

/**
 * Class ilCreateCourseMembershipValidation
 * @package ILIAS\Course
 */
class ilCreateCourseMembershipValidation implements Validation
{
	public function validate(ilCreateCourseMembersipCommand $command)
	{
		global $DIC;
		$validation_succedding = true;
        	if(!ilObject::_exists($DIC->refinery()->to()->int()->transform($command->getCourseObjId())))
        	{
			
	            	$DIC->logger()->root()->warning(__CLASS__ . ': Course Object Id is invalid');
        	    	$this->addToValidationFailures('Course Object Id is invalid');
			$validation_succedding = false;
        	}

		foreach($command->getUserObjIDs() as $user_obj_id)
		{
			if(!ilObjUser::_exists($DIC->refinery()->to()->int()->transform($user_obj_id))) {
				$DIC->logger()->root()->warning(__CLASS__ . ': User Object Id is invalid');
				$this->addToValidationFailures('User Object Id is invalid');
				$validation_succedding = false;
			}
		}

	        if(!in_array($DIC->refinery()->to()->int()->transform($command->getLocalRoleId()), array(1,2,3))) {
        	    	$DIC->logger()->root()->warning(__CLASS__ . ': Local Role is invalid');
        		$this->addToValidationFailures('Local Role is invalid');
			$validation_succedding = false;
        	}

        	if(!$DIC->rbac()->system()->checkAccessOfUser(
            		$DIC->refinery()->to()->int()->transform($command->getActorUserObjID()),
	            	'write',
            		current(ilObject::_getAllReferences($command->getCourseObjID()))
            		)
        	) {
	            	$DIC->logger()->root()->warning(__CLASS__ . ': No permission for user');
            		$this->addToValidationFailures('No permission for user');
			$validation_succedding = false;
        }
	
```

The validation MAY cache parts of the results, if it is safe to do so. The validation of the consumer, as described above, will not replace the validation during the dispatch, but things that are most likely to be static during the course of the request can be reused.

Also, it's an example. Much is written out so you can grasp the gist. It is believed in an actual wildlife specimen, it'll more look like this, with functions brought to logically grouped bundles:


```php 
	public function validate(ilCreateCourseMembersipCommand $command)
	{
		$course_api_checks = ilCourseAPIChecks::getInstance();
		$course_api_checks->checkCourseExistenceByID($command->getCourseObjID(), $this);
		$course_api_checks->checkUserExistenceByList($command->getUserObjIDs(), $this);
		$course_api_checks->checkRoleIDisValid($command->getLocalRoleID(), $this);
		$course_api_checks->checkActorPermission($command->getActorUserObjID(), $command->getCourseObjID(), 'write', $this);

		global $DIC;
	        $DIC->logger()->root()->debug('Command object ' . __CLASS__ . ' validated');
		
		if(count($this->validationFailures) == 0) return true;

		return false;
    }
```

It makes a whole lot of sense to bundle generic checks into a third place, reusing them all over your API objects.

## The Command Handler

So it's called command handler, but it's the same procedure for a query handler.
It takes not *a* request object but *the* request object. In the dispatching mechanism of the API, the command bus, a stable 1:1 connection is mapped. We intentionally make it hard to circumvent this, so we have a thoroughly checked value object and won't allow anyone to sneak in derivatives and make you do things based on improperly vetted objects. It's not perfectly safe, we're all clever enough, but we made it a point to require some basic effort to get around this, so you can always rule out "ignorance on rules" as reason if someone makes unsafe calls to you.

The command handler in this case looks like this:

```php
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
		try {
			$participant_object->add($command->getUserObjId(), $command->getLocalRoleId());
        } 
		catch (Exception $e) {
			return new ilCreateCourseMembershipResult(false, $e->getMessage());
		}
		
		return new ilCreateCourseMembershipResult($command, true);
    }
}
```

This is easy stuff, there is a single public method "handle", it takes your request object and executes. The security/validation will be called before.
You should see to catching all sorts of exceptions here, so your response object always comes back, even if it's transmitting word of an exception that happened.

## The Response Object

Another simple one, a messenger value-object. We have only one rule here to follow: If your code didn't execute, you must provide an error message.

```php
<?php
class ilCreateCourseMembershipResult extends ilEmptyCommandResult { }
```

where this is the empty command result or even base command result:

```php
<?php
class ilEmptyCommandResult implements \ILIAS\DomainLevelAPI\CommandResult 
{
	protected $ok_status;
	protected $command;
	protected $error_message;
	
    public function __construct(Command $command, bool $ok_status, string $message = null)
    {
		$this->command = $command;
		$this->ok_status = $ok_status;
		if(!$this->ok_status && $message == null)
		{
            $DIC->logger()->root()->warning(__CLASS__ . ': On not-ok status, error message is mandatory');
            throw new InvalidArgumentException(__CLASS__ . ': On not-ok status, error message is mandatory');
		}
		$this->message = $message;
	}
}
```

If done right, you will always return a value-object and save your consumers from Exceptions.

### Documentation

The documentation of the API will be created based on Docblocks. The control structure reload finds the classes based on the naming scheme and bundles them together based on the directory they are found in.
E.g. Modules/Course/classes/Commands has ilCreateCourseMembershipCommand, ilCreateCourseMembershipHandler and ilCreateCourseMembershipResult.
These will lead to a page in "Course" in the documentation, where all parameters (with types) and return values are discussed. Details of this are still TBD.

### In a nutshell

1. You MUST provide a request-class following a naming scheme: il<title>Command or il<title>Query
2. You MUST provice a security-/validation-class following a naming scheme il<title>Validation
3. The validation-class MAY cache parts of the validation for reuse by the dispatcher
4. The request class MAY ensure security and validity during object instantiation
5. You MUST provide a handler-class following a naming scheme: il<title>Handler
6. You MUST implement the Command interface on your handler, providing a method "handle" that takes a request object
7. You MUST catch exceptions during the execution of the command or query
8. You MUST deliver meaningful error information through the responst ojbect if they occur
9. You MUST deliver a response-class following a naming scheme: il<title>Result
10. Your response class MUST implement the CommandResult interface.
11. Your response class MUST be immutable
12. All API classes MUST reside in a common (TBD) subdirectory of classes/.
13. You MUST document your classes with docblocks. Documentation MUST always reflect the actual code works.

## Behind the Scenes

If you are interested in what happens between handing in your request object and it arriving at your command handler, this section is for you.

Request objects are fed into a command bus. In a nutshell, the command bus looks up the validator for a specific request and executes it. If the checks are passed, the command bus looks up the handler for that specific request and invokes it. The maps it uses for these purposes are generated during control structure reloads and persisted in the database or artifacts. The fixed and concrete mapping serves an important purpose: It makes sure that the handler gets the one request it was made for. By ensuring that, we keep developers from making "special requests" that better fit their needs but maybe slack on security. Also, vetting the request objects prior to execution helps to ensure safe opertion, too. To balance that out, the API itself does not enforce a higher level creation pattern for the request-instances, giving back flexibility to consumers.

### Integrating Sub-APIs

ILIAS objects as they appear in the user interface are taking advantage of services. Such can be PDF generation, Page Editor, Meta-Tags, Import and Export. These are, by this concept, not considered domain level objects "by default". So it's not "the page editor in the course container" to operate on but we'd instead think of "Editing the courses container page". Still it is clear that we will not be fully ignorant of the fact that this way of thinking leads to a whole lot of repetetive work across all domain level objects that use such services.
It would be desirable if service maintainers would provide classes that can be derived from by module maintainers, so they can extends on specific checks and eventually "enrich" the objects with contextual information. Services are supposed to only offer their high-level functionality, so the number of such offerings will be limited to what best resembles use cases. Still, responsibility will be at the domain level objects maintainer to offer such methods in a safe and sane way.

## Conclusion

The Aligned API as described in this concept is based on most simple ideas, trying to leave out as many complexities as possible to allow for ease on all sides. We have an easy path to create and publish API methods and using them is also easy. The API enforces security and additional safety can easily be applied, e.g. by giving a concrete spot to review security code. Beyond that, the footprint of the API is small and should fit well into other architectural models, it will never really be part of.
