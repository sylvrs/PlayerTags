<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use pocketmine\player\Player;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\plugin\Plugin;

class CombatLoggerTagGroup extends PluginTagGroup {

	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "CombatLogger");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 * @noinspection PhpUndefinedMethodInspection
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("timer", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return (string) $plugin->getTagDuration($player) ?? "";
			})
		];
	}
}