<?php


namespace sys\jordan\tags\tag\group\defaults;

use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use room17\SkyBlock\island\RankIds;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;

class SkyBlockTagGroup extends PluginTagGroup {

	/**
	 * SkyBlockTagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "SkyBlock");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 * @noinspection PhpUndefinedClassInspection
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("island_category", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				$session = $plugin->getSessionManager()->getSession($player);
				return $session->hasIsland() ? $session->getIsland()->getCategory() : "N/A";
			}),
			new ExternalPluginTag("island_rank", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				switch($plugin->getSessionManager()->getSession($player)->getRank()) {
					case RankIds::MEMBER:
						return "Member";
					case RankIds::OFFICER:
						return "Officer";
					case RankIds::LEADER:
						return "Leader";
					case RankIds::FOUNDER:
						return "Founder";
					default:
						return "Unknown";
				}
			}),
			new ExternalPluginTag("island_type", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				$session = $plugin->getSessionManager()->getSession($player);
				return $session->hasIsland() ? $session->getIsland()->getType() : "N/A";
			})
		];
	}
}