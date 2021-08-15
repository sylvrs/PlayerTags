<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

class RankUpTagGroup extends PluginTagGroup {

	/**
	 * RankUpTagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "RankUp");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 * @noinspection PhpUndefinedMethodInspection
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("rankup", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return ($group = $plugin->getPermManager()->getGroup($player)) !== false ? $group : "N/A";
			})
		];
	}
}