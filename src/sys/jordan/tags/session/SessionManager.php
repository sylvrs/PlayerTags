<?php

declare(strict_types=1);

namespace sys\jordan\tags\session;


use Exception;
use pocketmine\Player;
use pocketmine\utils\UUID;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;

class SessionManager {
	use PlayerTagsBaseTrait;

	/** @var array<string,SessionData> */
	protected array $incomingSessionData = [];
	/** @var array<string, PlayerSession> */
	private array $sessions = [];

	public function __construct(PlayerTagsBase $plugin) {
		$this->setPlugin($plugin);

	}

	/**
	 * In the case of reload, kick the players to ensure valid sessions
	 */
	public function onEnable(): void {
		$players = $this->getPlugin()->getServer()->getOnlinePlayers();
		if(count($players) > 0) {
			foreach($players as $player) {
				$player->kick("Invalid session detected. Please join back to validate your session!", false);
			}
		}
	}

	/**
	 * @throws Exception
	 */
	public function create(Player $player): void {
		$data = $this->getSessionData($player);
		if($data === null) {
			throw new Exception("Player {$player->getName()} doesn't have incoming session data");
		}
		$this->getPlugin()->getLogger()->debug("Creating player session...");
		$this->sessions[$player->getUniqueId()->toString()] ??= PlayerSession::create($player, $data);
		$this->getPlugin()->getLogger()->debug("Deleting session data...");
		$this->removeSessionData($player->getUniqueId());
	}

	public function exists(Player $player): bool {
		return isset($this->sessions[$player->getUniqueId()->toString()]);
	}

	public function fetch(Player $player): PlayerSession {
		return $this->sessions[$player->getUniqueId()->toString()];
	}

	public function delete(Player $player): void {
		if(isset($this->sessions[$player->getUniqueId()->toString()])) {
			$this->getPlugin()->getLogger()->debug("Deleting player session...");
			($this->sessions[$player->getUniqueId()->toString()])->cancel();
			unset($this->sessions[$player->getUniqueId()->toString()]);
		}
		if(isset($this->incomingSessionData[$player->getUniqueId()->toString()])) {
			$this->getPlugin()->getLogger()->debug("Deleting incoming session data for {$player->getName()}...");
			unset($this->incomingSessionData[$player->getUniqueId()->toString()]);
		}
	}

	public function createSessionData(UUID $uuid, array $loginData): void {
		if($this->hasSessionData($uuid)) {
			$this->getPlugin()->getLogger()->debug("Found existing incoming session data. Deleting...");
			$task = $this->incomingSessionData[$uuid->toString()]->getCheckTask();
			if($task->getHandler() !== null) {
				$this->getPlugin()->getLogger()->debug("Found awaiting check task for session. Canceling...");
				$task->getHandler()->cancel();
			}
			$this->removeSessionData($uuid);
		}
		$this->getPlugin()->getLogger()->debug("Creating session data...");
		$data = SessionData::create($uuid, $loginData);
		$this->getPlugin()->getScheduler()->scheduleDelayedTask($data->getCheckTask(), SessionData::CHECK_DELAY * 20);
		$this->incomingSessionData[$uuid->toString()] = $data;
	}

	public function getSessionData(Player $player): ?SessionData {
		return $this->incomingSessionData[$player->getUniqueId()->toString()] ?? null;
	}

	public function hasSessionData(UUID $uuid): bool {
		return isset($this->incomingSessionData[$uuid->toString()]);
	}

	public function removeSessionData(UUID $uuid): void {
		if($this->hasSessionData($uuid)) {
			$data = $this->incomingSessionData[$uuid->toString()];
			$task = $data->getCheckTask();
			if($task->getHandler() !== null) {
				$this->getPlugin()->getLogger()->debug("Found existing check task. Canceling...");
				$task->getHandler()->cancel();
			}
			unset($this->incomingSessionData[$uuid->toString()]);
		}
	}

}