<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;

use Closure;
use pocketmine\player\Player;
use function str_ireplace;

class Tag {

	private string $name;

	protected Closure $replaceCallback;

	public function __construct(string $name, Closure $replaceCallback) {
		$this->name = $name;
		$this->replaceCallback = $replaceCallback;
	}

	public function getName(): string {
		return $this->name;
	}

	public function replace(Player $player, string &$input): void {
		$output = ($this->replaceCallback)($player);
		if($output === null) return;
		$input = str_ireplace("{{$this->getName()}}", $output, $input);
	}

}