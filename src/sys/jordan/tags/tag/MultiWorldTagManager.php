<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;


use pocketmine\player\Player;
use pocketmine\utils\Config;
use function array_key_exists;

class MultiWorldTagManager {

	/** @var TagFactory */
	private $factory;

	/** @var bool */
	private $enabled;

	/** @var string[] */
	private $tags;

	/**
	 * MultiWorldTagManager constructor.
	 * @param TagFactory $factory
	 * @param Config $config
	 */
	public function __construct(TagFactory $factory, Config $config) {
		$this->factory = $factory;
		$this->enabled = $config->getNested("multi-world.enabled", false);
		$this->tags = $config->getNested("multi-world.worlds", []);
	}

	/**
	 * @return bool
	 */
	public function isEnabled(): bool {
		return $this->enabled;
	}

	/**
	 * @return TagFactory
	 */
	public function getFactory(): TagFactory {
		return $this->factory;
	}

	/**
	 * @return string[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * @param string $key
	 * @return string|null
	 */
	public function getTag(string $key): ?string {
		return $this->tags[$key] ?? null;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasTag(string $key): bool {
		return array_key_exists($key, $this->tags);
	}

	/**
	 * @param Player $player
	 * @return string
	 */
	public function getTagForLevel(Player $player): string {
		return ($this->isEnabled() && $player->isValid() && $this->hasTag($player->getLevel()->getFolderName())) ? $this->getTag($player->getLevel()->getFolderName()) : $this->getFactory()->getTagString();
	}

}