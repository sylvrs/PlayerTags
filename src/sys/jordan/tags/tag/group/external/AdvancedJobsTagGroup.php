<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\external;


use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\ExternalPluginTag;
use sys\jordan\tags\tag\group\PluginTagGroup;
use sys\jordan\tags\tag\TagFactory;

class AdvancedJobsTagGroup extends PluginTagGroup {

	/**
	 * AdvancedJobsTagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		parent::__construct($plugin, "AdvancedJobs");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 * @noinspection PhpUndefinedMethodInspection
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("job", $this->externalPlugin, function (Player $player, Plugin $plugin): string {
				return $plugin->hasJob($player) ? $plugin->getJob($player) : "Unemployed";
			}),
			new ExternalPluginTag("job_information", $this->externalPlugin, function (Player $player, Plugin $plugin): string {
				return $plugin->hasJob($player) ? $plugin->getJobInformation($player) : "";
			}),
			new ExternalPluginTag("job_progress", $this->externalPlugin, function (Player $player, Plugin $plugin): string {
				return $plugin->hasJob($player) ? (string) $plugin->getProgress($player) : "-1";
			})
		];
	}

}