<?php

declare(strict_types=1);

namespace sys\jordan\tags\type;


final class InputMode {

	/** @var int */
	const KEYBOARD = 1;
	/** @var int */
	const TOUCH = 2;
	/** @var int */
	const CONTROLLER = 3;

	/**
	 * no-op
	 */
	public function __construct() {}

}