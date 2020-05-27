<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;

use Exception;
use pocketmine\scheduler\ClosureTask;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\group\defaults\CombatLoggerTagGroup;
use sys\jordan\tags\tag\group\defaults\DefaultTagGroup;
use sys\jordan\tags\tag\group\defaults\EconomyAPITagGroup;
use sys\jordan\tags\tag\group\defaults\FactionsProTagGroup;
use sys\jordan\tags\tag\group\defaults\PiggyFactionsTagGroup;
use sys\jordan\tags\tag\group\defaults\PurePermsTagGroup;
use sys\jordan\tags\tag\group\defaults\RankUpTagGroup;
use sys\jordan\tags\tag\group\defaults\RedSkyBlockTagGroup;
use sys\jordan\tags\tag\group\TagGroup;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use function array_key_exists;
use function count;
use function str_ireplace;
use function str_replace;
use function strlen;

class TagFactory {

	use PlayerTagsBaseTrait;

	/** @var Tag[] */
	private $tags = [];

	/** @var string */
	private $tag;

	/** @var string */
	private $colorCharacter;

	/** @var int */
	private $updatePeriod;

	/**
	 * TagFactory constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		$this->setPlugin($plugin);
		$this->tag = $plugin->getConfig()->get("tag", "");
		$this->colorCharacter = $plugin->getConfig()->get("color-character", "&");
		$this->updatePeriod = $plugin->getConfig()->get("update-period", 10);
		$this->registerTags();
	}

	public function enable(): void {
		/*
		 * Only start task if the tag string length > 0
		 */
		if(strlen($this->getTagString()) > 0) {
			$this->getPlugin()->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (int $currentTick): void {
				$this->update();
			}), $this->getUpdatePeriod());
		}
	}

	/**
	 * @return string
	 */
	public function getTagString(): string {
		return $this->tag;
	}

	/**
	 * @return string
	 */
	public function getColorCharacter(): string {
		return $this->colorCharacter;
	}

	/**
	 * @return int
	 */
	public function getUpdatePeriod(): int {
		return $this->updatePeriod;
	}

	public function registerTags(): void {
		$this->registerGroup(new CombatLoggerTagGroup($this->getPlugin()));
		$this->registerGroup(new DefaultTagGroup($this->getPlugin()));
		$this->registerGroup(new EconomyAPITagGroup($this->getPlugin()));
		$this->registerGroup(new FactionsProTagGroup($this->getPlugin()));
		$this->registerGroup(new PiggyFactionsTagGroup($this->getPlugin()));
		$this->registerGroup(new PurePermsTagGroup($this->getPlugin()));
		$this->registerGroup(new RankUpTagGroup($this->getPlugin()));
		$this->registerGroup(new RedSkyBlockTagGroup($this->getPlugin()));
		$count = count($this->getTags());
		$this->getPlugin()->getLogger()->info(TextFormat::YELLOW . "Successfully loaded $count tags!");
	}

	/**
	 * @param Tag $tag
	 * @param bool $force
	 */
	public function register(Tag $tag, bool $force = false): void {
		if(array_key_exists($tag->getName(), $this->tags) && !$force) {
			$this->getPlugin()->getLogger()->error(TextFormat::RED . "Attempted to register tag that's already been registered: {$tag->getName()}");
			return;
		}
		$this->tags[$tag->getName()] = $tag;
	}

	/**
	 * @param TagGroup $group
	 */
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

	/**
	 * @param string $input
	 */
	public function replaceVisuals(string &$input): void {
		$input = str_replace($this->getColorCharacter(), TextFormat::ESCAPE, $input);
		$input = str_ireplace("{line}", "\n", $input);
	}

	/**
	 * @param Player $player
	 * @return string
	 */
	public function replace(Player $player): string {
		if(strlen($this->tag) <= 0) {
			return "";
		}
		$output = $this->getTagString();
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
		foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $player) {
			$player->setScoreTag($this->replace($player));
		}
	}

}