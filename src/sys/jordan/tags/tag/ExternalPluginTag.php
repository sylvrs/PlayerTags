<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;

use pocketmine\Player;
use pocketmine\plugin\Plugin;
use function str_ireplace;

class ExternalPluginTag extends Tag {

	private Plugin $plugin;

	public function __construct(string $name, Plugin $externalPlugin, \Closure $replaceCallback) {
		parent::__construct($name, $replaceCallback);
		$this->plugin = $externalPlugin;
	}

	public function getPlugin(): Plugin {
		return $this->plugin;
	}

	public function replace(Player $player, string &$input): void {
		$output = ($this->replaceCallback)($player, $this->getPlugin());
		if($output === null) return;
		$input = str_ireplace("{{$this->getName()}}", $output, $input);
	}
}