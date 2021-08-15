<?php

declare(strict_types=1);

namespace sys\jordan\tags\session;


use pocketmine\player\Player;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;
use function array_filter;
use function array_key_exists;
use function array_search;

class SessionManager {

	use PlayerTagsBaseTrait;

	/** @var PlayerSession[] */
	private $sessions = [];

	/**
	 * SessionManager constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		$this->setPlugin($plugin);
	}

	/**
	 * In the case of a reload, kick the players to ensure valid sessions
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
	 * @param UUID $uuid
	 * @return PlayerSession|null
	 */
	public function create(UUID $uuid): PlayerSession {
		return ($this->sessions[$uuid->toString()] = new PlayerSession($uuid));
	}

	/**
	 * @param Player $player
	 */
	public function remove(Player $player): void {
		if(array_key_exists($player->getUniqueId()->toString(), $this->sessions)) {
			($this->sessions[$player->getUniqueId()->toString()])->destroy();
			unset($this->sessions[$player->getUniqueId()->toString()]);
		}
	}

	/**
	 * @param PlayerSession $session
	 */
	public function delete(PlayerSession $session) {
		if(($key = array_search($session, $this->sessions, true)) !== false) {
			$session->destroy();
			unset($this->sessions[$key]);
		}
	}

	/**
	 * @param Player $player
	 * @return PlayerSession|null
	 */
	public function find(Player $player): ?PlayerSession {
		return $this->sessions[$player->getUniqueId()->toString()] ?? null;
	}

	/**
	 * @return PlayerSession[]
	 */
	public function getSessions(): array {
		return $this->sessions;
	}

	public function clear(): void {
		$this->sessions = [];
	}
}