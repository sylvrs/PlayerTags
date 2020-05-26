<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class CombatLoggerTagGroup extends PluginTagGroup {

	/**
	 * CombatLoggerTagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "CombatLogger");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("timer", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return (string) $plugin->getTagDuration($player) ?? "";
			})
		];
	}
}