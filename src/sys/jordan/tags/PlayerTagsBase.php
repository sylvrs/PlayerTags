<?php

declare(strict_types=1);

namespace sys\jordan\tags;

use sys\jordan\tags\session\SessionManager;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class PlayerTagsBase extends PluginBase implements Listener {

	protected static self $instance;

	protected SessionManager $sessionManager;
	protected TagFactory $tagFactory;


	public function onLoad(): void {
		self::$instance = $this;
		$this->saveDefaultConfig();
		$this->sessionManager = new SessionManager($this);
		$this->tagFactory = new TagFactory($this);
	}

	public function onEnable(): void {
		$this->getTagFactory()->enable();
		new PlayerTagsListener($this);
	}

	public static function getInstance(): self {
		return self::$instance;
	}

	public function getSessionManager(): SessionManager {
		return $this->sessionManager;
	}

	public function getTagFactory(): TagFactory {
		return $this->tagFactory;
	}

}
