<?php

namespace sys\jordan\tags\session;

use pocketmine\utils\UUID;
use sys\jordan\tags\session\task\SessionCheckTask;

final class SessionData {

	/** @var int */
	public const CHECK_DELAY = 30;

	protected UUID $uuid;

	protected string $device;
	protected int $inputMode;
	protected int $os;

	protected SessionCheckTask $checkTask;

	public static function create(UUID $uuid, array $loginData): self {
		return new self($uuid, $loginData["DeviceModel"], $loginData["CurrentInputMode"], $loginData["DeviceOS"]);
	}

	public function __construct(UUID $uuid, string $device, int $inputMode, int $os) {
		$this->uuid = $uuid;
		$this->device = $device;
		$this->inputMode = $inputMode;
		$this->os = $os;

		$this->checkTask = new SessionCheckTask($uuid);
	}

	public function getDevice(): string {
		return $this->device;
	}

	public function getInputMode(): int {
		return $this->inputMode;
	}

	public function getOS(): int {
		return $this->os;
	}

	public function getCheckTask(): SessionCheckTask {
		return $this->checkTask;
	}

}