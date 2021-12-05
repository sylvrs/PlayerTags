<?php

declare(strict_types=1);

namespace sys\jordan\tags\session;


use pocketmine\player\Player;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;

class SessionManager {
	use PlayerTagsBaseTrait;

	/** @var PlayerSession[] */
	private array $sessions = [];

	/**
	 * SessionManager constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		$this->setPlugin($plugin);
	}

	public function get(Player $player): PlayerSession {
		return $this->sessions[$player->getUniqueId()->toString()] ??= PlayerSession::create($player);
	}

	public function remove(Player $player): void {
		if(isset($this->sessions[$player->getUniqueId()->toString()])) {
			($this->sessions[$player->getUniqueId()->toString()])->destroy();
			unset($this->sessions[$player->getUniqueId()->toString()]);
		}
	}

	public function delete(PlayerSession $session): void {
		if(isset($this->sessions[$session->getUuid()->toString()])) {
			$session->destroy();
			unset($this->sessions[$session->getUuid()->toString()]);
		}
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