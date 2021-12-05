<?php

declare(strict_types=1);

namespace sys\jordan\tags;

use sys\jordan\tags\session\SessionManager;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class PlayerTagsBase extends PluginBase implements Listener {

	private SessionManager $sessionManager;
	private TagFactory $tagFactory;
	private static self $instance;

	public function onLoad(): void {
		$this->getLogger()->warning("PlayerTags for API 3.0 is no longer actively supported. Please consider upgrading your server to the latest PocketMine version with the latest PlayerTags release.");
		self::$instance = $this;
		$this->saveDefaultConfig();
		$this->sessionManager = new SessionManager($this);
		$this->tagFactory = new TagFactory($this);
	}

	public function onEnable(): void {
		$this->getTagFactory()->enable();
		$this->getSessionManager()->onEnable();
		new PlayerTagsListener($this);
	}

	public static function getInstance(): PlayerTagsBase {
		return self::$instance;
	}

	public function getSessionManager(): SessionManager {
		return $this->sessionManager;
	}

	public function getTagFactory(): TagFactory {
		return $this->tagFactory;
	}

}
