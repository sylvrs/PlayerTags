<?php


namespace sys\jordan\tags\type;


final class DeviceOS {

	/** @var int */
	const ANDROID = 1;
	/** @var int */
	const IOS = 2;
	/** @var int */
	const OSX = 3;
	/** @var int */
	const FIRE_OS = 4;
	/** @var int */
	const GEAR_VR = 5;
	/** @var int */
	const HOLOLENS = 6;
	/** @var int */
	const WIN10 = 7;
	/** @var int */
	const WIN32 = 8;
	/** @var int  */
	const DEDICATED = 9;
	/** @var int  */
	const TV_OS = 10;
	/** @var int */
	const PS4 = 11;
	/** @var int */
	const SWITCH = 12;
	/** @var int */
	const XBOX = 13;
	/** @var int */
	const UNKNOWN = -1;

	/**
	 * no-op
	 */
	public function __construct() {}

}