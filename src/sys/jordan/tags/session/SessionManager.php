<?php

declare(strict_types=1);

namespace sys\jordan\tags\session;


use pocketmine\Player;
use pocketmine\utils\UUID;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;
use function array_key_exists;

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
	 * @param UUID $uuid
	 * @return PlayerSession|null
	 */
	public function create(UUID $uuid): ?PlayerSession {
		if(!array_key_exists($uuid->toString(), $this->sessions)) {
			return ($this->sessions[$uuid->toString()] = new PlayerSession($uuid));
		}
		return null;
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
	 * @return PlayerSession[]
	 */
	public function getSessions(): array {
		return $this->sessions;
	}

	/**
	 * @param Player $player
	 * @return PlayerSession|null
	 */
	public function findSession(Player $player): ?PlayerSession {
		return $this->sessions[$player->getUniqueId()->toString()] ?? null;
	}

	public function clear(): void {
		$this->sessions = [];
	}
}