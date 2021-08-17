<?php

namespace sys\jordan\tags\session\task;

use pocketmine\scheduler\Task;
use pocketmine\utils\UUID;
use sys\jordan\tags\PlayerTagsBase;

class SessionCheckTask extends Task {

	protected UUID $uuid;

	public function __construct(UUID $uuid) {
		$this->uuid = $uuid;
	}

	public function onRun(int $currentTick) {
		$plugin = PlayerTagsBase::getInstance();
		$sessionManager = $plugin->getSessionManager();
		if($sessionManager->hasSessionData($this->uuid) && $plugin->getServer()->getPlayerByUUID($this->uuid) === null) {
			$plugin->getLogger()->debug("Player went offline before session could be created. Deleting session data...");
			$sessionManager->removeSessionData($this->uuid);
		}
	}
}