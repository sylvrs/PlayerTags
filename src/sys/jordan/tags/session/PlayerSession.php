<?php

declare(strict_types=1);

namespace sys\jordan\tags\session;


use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\network\mcpe\protocol\types\InputMode;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use pocketmine\utils\UUID;
use sys\jordan\tags\PlayerTagsBase;
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
		PlayerTagsBase::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick): void {
			$plugin = PlayerTagsBase::getInstance();
			if(!isset($this->player) || !$this->player instanceof Player) {
				$plugin->getSessionManager()->delete($this);
				$plugin->getLogger()->debug("Deleting session due to inactivity");
			}
		}), 20 * 30);
	}

	public function start(PlayerTagsBase $plugin): void {
		$plugin->getScheduler()->scheduleRepeatingTask($this->getClickUpdateTask(), self::UPDATE_PERIOD);
	}

	/**
	 * @return UUID
	 */
	public function getUUID(): UUID {
		return $this->uuid;
	}

	public function getPlayer(): Player {
		return $this->player;
	}

	public function setPlayer(Player $player): void {
		$this->player = $player;
	}

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

	public function getDevice(): string {
		return $this->device;
	}

	public function setDevice(string $device): void {
		$this->device = $device;
	}

	public function getInputMode(): int {
		return $this->inputMode;
	}

	public function setInputMode(int $inputMode): void {
		$this->inputMode = $inputMode;
	}

	public function getInputModeString(): string {
		return match ($this->getInputMode()) {
			InputMode::MOUSE_KEYBOARD => "Keyboard",
			InputMode::TOUCHSCREEN => "Touch",
			InputMode::GAME_PAD => "Controller",
			default => "Unknown",
		};
	}

	public function getOS(): int {
		return $this->os;
	}

	public function setOS(int $os): void {
		$this->os = $os;
	}

	public function getOSString(): string {
		return match ($this->getOS()) {
			DeviceOS::ANDROID => "Android",
			DeviceOS::IOS => "iOS",
			DeviceOS::OSX => "MacOS",
			DeviceOS::AMAZON => "FireOS",
			DeviceOS::GEAR_VR => "GearVR",
			DeviceOS::HOLOLENS => "HoloLens",
			DeviceOS::WINDOWS_10 => "Windows 10",
			DeviceOS::WIN32 => "Windows 32",
			DeviceOS::DEDICATED => "Dedicated",
			DeviceOS::TVOS => "tvOS",
			DeviceOS::PLAYSTATION => "PS4",
			DeviceOS::NINTENDO => "Switch",
			DeviceOS::XBOX => "Xbox",
			DeviceOS::WINDOWS_PHONE => "Windows Phone",
			default => "Unknown",
		};
	}

	public function destroy(): void {
		if($this->clickUpdateTask->getHandler() instanceof TaskHandler) {
			$this->clickUpdateTask->getHandler()->cancel();
		}
		foreach($this as $key => $value) unset($this->$key);
	}

}