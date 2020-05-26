<?php

declare(strict_types=1);


namespace sys\jordan\tags\tag\group\defaults;


use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class PurePermsTagGroup extends PluginTagGroup {

	/**
	 * PurePermsTagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "PurePerms");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("rank", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getUserDataMgr()->getGroup($player)->getName() ?? "N/A";
			}),
			new ExternalPluginTag("prefix", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getUserDataMgr()->getNode($player, "prefix") ?? "";
			}),
			new ExternalPluginTag("suffix", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getUserDataMgr()->getNode($player, "suffix") ?? "";
			})
		];
	}
}