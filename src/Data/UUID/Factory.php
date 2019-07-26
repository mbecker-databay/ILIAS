<?php

namespace ILIAS\Data\UUID;


use Exception;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Factory
 * @package ILIAS\Data\UUID
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class Factory {


	/**
	 * @var UuidFactory
	 */
	private $uuid_factory;

	/**
	 * Factory constructor.
	 */
	public function __construct() {
		$this->uuid_factory = new UuidFactory();
	}

	/**
	 * @return UuidInterface
	 * @throws Exception
	 */
	public function uuid4() {
		return $this->uuid_factory->uuid4();
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function uuid4AsString() {
		return $this->uuid4()->toString();
	}

}