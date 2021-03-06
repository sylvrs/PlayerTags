<?php

declare(strict_types=1);

namespace sys\jordan\tags;

use sys\jordan\tags\session\SessionManager;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class PlayerTagsBase extends PluginBase implements Listener {

	/** @var SessionManager */
	private $sessionManager;

	/** @var TagFactory */
	private $tagFactory;

	/** @var self */
	private static $instance;

	public function onLoad(): void {
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

	/**
	 * @return PlayerTagsBase
	 */
	public static function getInstance(): PlayerTagsBase {
		return self::$instance;
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
