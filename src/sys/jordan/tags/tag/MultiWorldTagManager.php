<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;


use pocketmine\player\Player;
use pocketmine\utils\Config;

class MultiWorldTagManager {

	private TagFactory $factory;
	private bool $enabled;

	/** @var string[] */
	private array $tags;

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

	public function isEnabled(): bool {
		return $this->enabled;
	}

	public function getFactory(): TagFactory {
		return $this->factory;
	}

	/**
	 * @return string[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	public function getTag(string $key): ?string {
		return $this->tags[$key] ?? null;
	}

	public function hasTag(string $key): bool {
		return isset($this->tags[$key]);
	}

	public function getTagForWorld(Player $player): string {
		return ($this->isEnabled() && $player->getLocation()->isValid() && $this->hasTag($player->getWorld()->getFolderName())) ? $this->getTag($player->getWorld()->getFolderName()) : $this->getFactory()->getTagString();
	}

}