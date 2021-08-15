<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;

use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use function str_ireplace;

class ExternalPluginTag extends Tag {

	protected Plugin $plugin;

	/**
	 * ExternalPluginTag constructor.
	 * @param string $name
	 * @param Plugin $externalPlugin
	 * @param callable $replaceCallback
	 */
	public function __construct(string $name, Plugin $externalPlugin, callable $replaceCallback) {
		parent::__construct($name, $replaceCallback);
		$this->plugin = $externalPlugin;
	}

	public function getPlugin(): Plugin {
		return $this->plugin;
	}

	/**
	 * @param Player $player
	 * @param string $input
	 */
	public function replace(Player $player, string &$input): void {
		$output = ($this->replaceCallback)($player, $this->getPlugin());
		if($output === null) return;
		$input = str_ireplace("{". $this->getName() . "}", $output, $input);
	}
}