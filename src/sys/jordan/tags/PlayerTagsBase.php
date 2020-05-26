<?php

declare(strict_types=1);

namespace sys\jordan\tags;

use sys\jordan\tags\session\SessionManager;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class PlayerTagsBase extends PluginBase implements Listener {

	/** @var SessionManager */
	private $sessionManager;

	/** @var TagFactory */
	private $tagFactory;

	public function onLoad(): void {
		$this->saveDefaultConfig();
		$this->sessionManager = new SessionManager($this);
		$this->tagFactory = new TagFactory($this);
	}

	public function onEnable(): void {
		$this->getTagFactory()->enable();
		new PlayerTagsListener($this);
		$this->getLogger()->info(TextFormat::GREEN . "{$this->getDescription()->getFullName()} has been enabled successfully!");
	}

	/**
	 * @return SessionManager
	 */
	public function getSessionManager(): SessionManager {
		return $this->sessionManager;
	}

	/**
	 * @return TagFactory
	 */
	public function getTagFactory(): TagFactory {
		return $this->tagFactory;
	}

}
