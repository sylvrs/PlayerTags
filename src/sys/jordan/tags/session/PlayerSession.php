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

	private UUID $uuid;
	private Player $player;

	private string $device;
	private int $inputMode;
	private int $os;

	/** @var float[] */
	private array $clicks = [];
	private float $clicksPerSecond = 0.0;
	private ClosureTask $clickUpdateTask;

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
		// Count the number of clicks in that second by comparing the timestamps against the current time
		$this->clicksPerSecond = count(array_filter($this->clicks, function (float $timestamp) use($current): bool { return ($current - $timestamp) <= 1; }));
	}

	public function getClickUpdateTask(): ClosureTask {
		return $this->clickUpdateTask;
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
		switch($this->getInputMode()) {
			case InputMode::MOUSE_KEYBOARD:
				return "Keyboard";
			case InputMode::TOUCHSCREEN:
				return "Touch";
			case InputMode::GAME_PAD:
				return "Controller";
			default:
				return "Unknown";
		}
	}

	public function getOS(): int {
		return $this->os;
	}

	public function setOS(int $os): void {
		$this->os = $os;
	}

	public function getOSString(): string {
		switch($this->getOS()) {
			case DeviceOS::ANDROID:
				return "Android";
			case DeviceOS::IOS:
				return "iOS";
			case DeviceOS::OSX:
				return "MacOS";
			case DeviceOS::AMAZON:
				return "FireOS";
			case DeviceOS::GEAR_VR:
				return "GearVR";
			case DeviceOS::HOLOLENS:
				return "HoloLens";
			case DeviceOS::WINDOWS_10:
				return "Windows 10";
			case DeviceOS::WIN32:
				return "Windows 32";
			case DeviceOS::DEDICATED:
				return "Dedicated";
			case DeviceOS::TVOS:
				return "tvOS";
			case DeviceOS::PLAYSTATION:
				return "PS4";
			case DeviceOS::NINTENDO:
				return "Switch";
			case DeviceOS::XBOX:
				return "Xbox";
			case DeviceOS::WINDOWS_PHONE:
				return "Windows Phone";
			default:
				return "Unknown";
		}
	}

	public function destroy(): void {
		if($this->clickUpdateTask->getHandler() instanceof TaskHandler) {
			$this->clickUpdateTask->getHandler()->cancel();
		}
		foreach($this as $key => $value) unset($this->$key);
	}

}