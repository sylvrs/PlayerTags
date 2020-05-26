<?php

declare(strict_types=1);

namespace sys\jordan\tags\session;


use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\UUID;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\type\DeviceOS;
use sys\jordan\tags\type\InputMode;
use function array_filter;
use function array_shift;
use function count;
use function microtime;

class PlayerSession {

	/** @var int */
	public const MAX_CPS = 50;
	/** @var int */
	public const UPDATE_PERIOD = 5;

	/** @var UUID */
	private $uuid;

	/** @var Player */
	private $player;

	/** @var string */
	private $device;

	/** @var int */
	private $inputMode;

	/** @var int */
	private $os;

	/** @var int[] */
	private $clicks = [];

	/** @var float */
	private $clicksPerSecond = 0.0;

	/** @var ClosureTask */
	private $clickUpdateTask;

	/**
	 * PlayerSession constructor.
	 * @param UUID $uuid
	 */
	public function __construct(UUID $uuid) {
		$this->uuid = $uuid;
		$this->clickUpdateTask = new ClosureTask(function (int $currentTick): void {
			$this->calculateClicksPerSecond();
		});
	}

	/**
	 * @param PlayerTagsBase $plugin
	 */
	public function start(PlayerTagsBase $plugin): void {
		$plugin->getScheduler()->scheduleRepeatingTask($this->getClickUpdateTask(), self::UPDATE_PERIOD);
	}

	/**
	 * @return UUID
	 */
	public function getUUID(): UUID {
		return $this->uuid;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->player;
	}

	/**
	 * @param Player $player
	 */
	public function setPlayer(Player $player): void {
		$this->player = $player;
	}

	/**
	 * @return float
	 */
	public function getClicksPerSecond(): float {
		return $this->clicksPerSecond;
	}

	/**
	 * Pushes a timestamp to an array of clicks
	 * Used to calculate the player's current CPS
	 */
	public function addClick(): void {
		$this->clicks[] = microtime(true);
		if(count($this->clicks) > self::MAX_CPS) array_shift($this->clicks);
	}

	public function clearClicks(): void {
		$this->clicks = [];
		$this->clicksPerSecond = 0;
	}


	public function calculateClicksPerSecond(): void {
		if(count($this->clicks) <= 0) return;
		$current = microtime(true);
		/*
		 * Count the number of clicks in that second by comparing the timestamps against the current time
		 */
		$this->clicksPerSecond = count(array_filter($this->clicks, function (float $timestamp) use($current): bool { return ($current - $timestamp) <= 1; }));
	}

	/**
	 * @return ClosureTask
	 */
	public function getClickUpdateTask(): ClosureTask {
		return $this->clickUpdateTask;
	}

	/**
	 * @return string
	 */
	public function getDevice(): string {
		return $this->device;
	}

	/**
	 * @param string $device
	 */
	public function setDevice(string $device): void {
		$this->device = $device;
	}

	/**
	 * @return int
	 */
	public function getInputMode(): int {
		return $this->inputMode;
	}

	/**
	 * @param int $inputMode
	 */
	public function setInputMode(int $inputMode): void {
		$this->inputMode = $inputMode;
	}

	/**
	 * @return string
	 */
	public function getInputModeString(): string {
		switch($this->getInputMode()) {
			case InputMode::KEYBOARD:
				return "KB+M";
			case InputMode::TOUCH:
				return "Touch";
			case InputMode::CONTROLLER:
				return "Controller";
			default:
				return "Unknown";
		}
	}

	/**
	 * @return int
	 */
	public function getOS(): int {
		return $this->os;
	}

	/**
	 * @param int $os
	 */
	public function setOs(int $os): void {
		$this->os = $os;
	}

	/**
	 * @return string
	 */
	public function getOSString(): string {
		switch($this->getOS()) {
			case DeviceOS::ANDROID:
				return "Android";
			case DeviceOS::IOS:
				return "iOS";
			case DeviceOS::OSX:
				return "MacOS";
			case DeviceOS::FIRE_OS:
				return "FireOS";
			case DeviceOS::GEAR_VR:
				return "GearVR";
			case DeviceOS::HOLOLENS:
				return "HoloLens";
			case DeviceOS::WIN10:
				return "Windows 10";
			case DeviceOS::WIN32:
				return "Windows 32";
			case DeviceOS::DEDICATED:
				return "Dedicated";
			case DeviceOS::TV_OS:
				return "tvOS";
			case DeviceOS::PS4:
				return "PS4";
			case DeviceOS::SWITCH:
				return "Switch";
			case DeviceOS::XBOX:
				return "Xbox";
			default:
				return "Unknown";
		}
	}

	public function destroy(): void {
		$this->clickUpdateTask->getHandler()->cancel();
		foreach($this as $key => $value) unset($this->$key);
	}

}