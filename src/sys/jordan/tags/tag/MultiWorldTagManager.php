<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;


use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use sys\jordan\tags\tag\migration\MigrationChecker;

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
		if(count($this->tags) > 0 && $this->enabled) {
			$plugin = $this->getFactory()->getPlugin();
			foreach($this->tags as $world => $tag) {
				$plugin->getLogger()->debug("Checking multi-world tags for outdated tag types...");
				$result = MigrationChecker::checkTag($tag);
				if($result->hasChanged()) {
					foreach($result->changed as $old => [$new, $count]) {
						$plugin->getLogger()->debug("Located $count tag(s) of type $old. Converting to type $new...");
					}
					$plugin->getLogger()->debug("Migrating outdated tags to new types...");
					$this->tags[$world] = $result->newTag;
					// Save changes to disk
					$config->setNested("multi-world.worlds", $this->tags);
					$plugin->getConfig()->save();
					$plugin->getLogger()->info(TextFormat::YELLOW . "Outdated tags were found for world '$world' and have been successfully migrated!");
				}
			}
		}
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