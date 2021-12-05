<?php

/**
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedMethodInspection
 * @noinspection PhpUndefinedClassInspection
 */
declare(strict_types=1);

namespace sys\jordan\tags\tag\group\external;

use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use room17\SkyBlock\island\RankIds;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;

class SkyBlockTagGroup extends PluginTagGroup {

	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "SkyBlock");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("island_category", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				$session = $plugin->getSessionManager()->getSession($player);
				return $session->hasIsland() ? $session->getIsland()->getCategory() : "N/A";
			}),
			new ExternalPluginTag("island_rank", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return match ($plugin->getSessionManager()->getSession($player)->getRank()) {
					RankIds::MEMBER => "Member",
					RankIds::OFFICER => "Officer",
					RankIds::LEADER => "Leader",
					RankIds::FOUNDER => "Founder",
					default => "Unknown",
				};
			}),
			new ExternalPluginTag("island_type", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				$session = $plugin->getSessionManager()->getSession($player);
				return $session->hasIsland() ? $session->getIsland()->getType() : "N/A";
			})
		];
	}
}