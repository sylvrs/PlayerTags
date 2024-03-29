<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;

use Exception;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\group\DefaultTagGroup;
use sys\jordan\tags\tag\group\external\AdvancedJobsTagGroup;
use sys\jordan\tags\tag\group\external\CombatLoggerTagGroup;
use sys\jordan\tags\tag\group\external\EconomyAPITagGroup;
use sys\jordan\tags\tag\group\external\FactionsProTagGroup;
use sys\jordan\tags\tag\group\external\KDRTagGroup;
use sys\jordan\tags\tag\group\external\PiggyFactionsTagGroup;
use sys\jordan\tags\tag\group\external\PurePermsTagGroup;
use sys\jordan\tags\tag\group\external\RankUpTagGroup;
use sys\jordan\tags\tag\group\external\RedSkyBlockTagGroup;
use sys\jordan\tags\tag\group\external\SkyBlockTagGroup;
use sys\jordan\tags\tag\group\TagGroup;
use sys\jordan\tags\tag\migration\MigrationChecker;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;
use pocketmine\utils\TextFormat;

use function count;
use function str_ireplace;
use function str_replace;
use function strlen;

class TagFactory {

	use PlayerTagsBaseTrait;

	/** @var Tag[] */
	private array $tags = [];

	private string $tag;
	private string $colorCharacter;

	private int $updatePeriod;

	private MultiWorldTagManager $tagManager;


	public function __construct(PlayerTagsBase $plugin) {
		$this->setPlugin($plugin);
		$this->tag = $plugin->getConfig()->get("tag", "");
		if(strlen($this->tag) > 0) {
			$result = MigrationChecker::checkTag($this->tag);
			$plugin->getLogger()->debug("Checking global tag for outdated tag types...");
			if($result->hasChanged()) {
				foreach($result->changed as $old => [$new, $count]) {
					$this->getPlugin()->getLogger()->debug("Located $count tag(s) of type $old. Converting to type $new...");
				}
				$this->getPlugin()->getLogger()->debug("Migrating outdated tags to new types...");
				$this->tag = $result->newTag;
				// Save changes to disk
				$plugin->getConfig()->set("tag", $this->tag);
				$plugin->getConfig()->save();
				$this->getPlugin()->getLogger()->info(TextFormat::YELLOW . "Outdated tags were found in the global tag and have automatically been converted to the new tag types.");
			}
		}

		$this->colorCharacter = $plugin->getConfig()->get("color-character", "&");
		$this->updatePeriod = $plugin->getConfig()->get("update-period", 10);
		$this->tagManager = new MultiWorldTagManager($this, $this->getPlugin()->getConfig());
		$this->registerTags();
	}

	public function enable(): void {
		if(strlen($this->getTagString()) > 0) {
			$this->getPlugin()->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (): void {
				$this->update();
			}), $this->getUpdatePeriod());
		}
	}

	public function getTagString(): string {
		return $this->tag;
	}

	public function getColorCharacter(): string {
		return $this->colorCharacter;
	}

	public function getUpdatePeriod(): int {
		return $this->updatePeriod;
	}

	public function getTagManager(): MultiWorldTagManager {
		return $this->tagManager;
	}

	public function registerTags(): void {
		$this->registerGroup(new DefaultTagGroup($this->getPlugin()));

		$this->registerGroup(new AdvancedJobsTagGroup($this->getPlugin()));
		$this->registerGroup(new CombatLoggerTagGroup($this->getPlugin()));
		$this->registerGroup(new EconomyAPITagGroup($this->getPlugin()));
		$this->registerGroup(new FactionsProTagGroup($this->getPlugin()));
		$this->registerGroup(new KDRTagGroup($this->getPlugin()));
		$this->registerGroup(new PiggyFactionsTagGroup($this->getPlugin()));
		$this->registerGroup(new PurePermsTagGroup($this->getPlugin()));
		$this->registerGroup(new RankUpTagGroup($this->getPlugin()));
		$this->registerGroup(new RedSkyBlockTagGroup($this->getPlugin()));
		$this->registerGroup(new SkyBlockTagGroup($this->getPlugin()));
		$count = count($this->getTags());
		$this->getPlugin()->getLogger()->info(TextFormat::YELLOW . "Successfully loaded $count tags!");
	}

	public function register(Tag $tag, bool $force = false): void {
		if(isset($this->tags[$tag->getName()]) && !$force) {
			$this->getPlugin()->getLogger()->error(TextFormat::RED . "Attempted to register tag that's already been registered: {$tag->getName()}");
			return;
		}
		$this->tags[$tag->getName()] = $tag;
	}

	public function registerGroup(TagGroup $group): void {
		$tags = $group->load($this);
		if(count($tags) > 0) {
			foreach($tags as $tag) $this->register($tag);
		}
	}

	/**
	 * @return Tag[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	public function replaceVisuals(string &$input): void {
		$input = str_replace($this->getColorCharacter(), TextFormat::ESCAPE, $input);
		$input = str_ireplace("{line}", "\n", $input);
	}

	public function replace(Player $player): string {
		if(strlen($this->tag) <= 0) {
			return "";
		}
		$output = $this->getTagManager()->getTagForWorld($player);
		foreach($this->getTags() as $tag) {
			try {
				$tag->replace($player, $output);
			} catch (Exception $exception) {
				//just in case a malformed tag callback happens
				$this->getPlugin()->getLogger()->logException($exception);
			}
		}
		$this->replaceVisuals($output);
		return $output;
	}

	public function update(): void {
		$players = array_filter($this->getPlugin()->getServer()->getOnlinePlayers(), static fn(Player $player): bool => $player->isOnline() && $player->spawned);
		foreach($players as $player) {
			$player->setScoreTag($this->replace($player));
		}
	}

}