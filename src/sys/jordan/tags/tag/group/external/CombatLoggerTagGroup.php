<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\external;


use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;

class CombatLoggerTagGroup extends PluginTagGroup {

	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "CombatLogger");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("timer", $this->externalPlugin, function (Player $player, Plugin $plugin): string {
				$tag = $plugin->getTag($player);
				return (string) ($tag?->getExpiryTimestamp() - $tag?->getCreationTimestamp()) ?? "";
			})
		];
	}
}