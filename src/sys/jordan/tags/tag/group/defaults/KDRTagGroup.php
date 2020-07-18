<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use pocketmine\Player;
use pocketmine\plugin\Plugin;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;

class KDRTagGroup extends PluginTagGroup {

	/**
	 * KDRTagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "KDR");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 * @noinspection PhpUndefinedMethodInspection
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("kills", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getProvider()->playerExists($player) ? (string) $plugin->getProvider()->getPlayerKillPoints($player) : "0";
			}),
			new ExternalPluginTag("deaths", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getProvider()->playerExists($player) ? (string) $plugin->getProvider()->getPlayerDeathPoints($player) : "0";
			}),
			new ExternalPluginTag("kdr", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getProvider()->playerExists($player) ? $plugin->getProvider()->getKillToDeathRatio($player) : "0.0";
			}),

		];
	}
}