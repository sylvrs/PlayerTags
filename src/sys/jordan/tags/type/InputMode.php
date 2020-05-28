<?php

declare(strict_types=1);

namespace sys\jordan\tags\type;


final class InputMode {

	/** @var int */
	public const KEYBOARD = 1;
	/** @var int */
	public const TOUCH = 2;
	/** @var int */
	public const CONTROLLER = 3;

	/**
	 * no-op
	 */
	private function __construct() {}

}