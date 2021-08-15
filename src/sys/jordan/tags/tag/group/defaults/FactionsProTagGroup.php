<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\player\Player;
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
	 * @noinspection PhpUndefinedMethodInspection
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("faction_name", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getPlayerFaction($player) ?? "None";
			}),
			new ExternalPluginTag("faction_power", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				$faction = $plugin->getPlayerFaction($player);
				return (string) ($faction !== null ?  $plugin->getFactionPower($faction) ?? "" : "");
			})
		];
	}
}