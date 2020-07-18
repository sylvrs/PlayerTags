<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class RedSkyBlockTagGroup extends PluginTagGroup {

	/**
	 * RedSkyBlockTagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "RedSkyBlock");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 * @noinspection PhpUndefinedMethodInspection
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("island_name", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getIslandName($player) ?? "";
			}),
			new ExternalPluginTag("island_rank", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->calcRank($player->getLowerCaseName()) ?? "";
			}),
			new ExternalPluginTag("island_value", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return (string) $plugin->getValue($player) ?? "";
			})
		];
	}
}