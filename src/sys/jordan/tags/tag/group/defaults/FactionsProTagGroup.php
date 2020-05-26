<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class FactionsProTagGroup extends PluginTagGroup {

	/**
	 * FactionsProTagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "FactionsPro");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("faction", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getPlayerFaction($player) ?? "None";
			}),
			new ExternalPluginTag("f_power", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return (string) $plugin->getFactionPower($plugin->getPlayerFaction($player)) ?? "";
			})
		];
	}
}