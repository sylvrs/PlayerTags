<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;

use pocketmine\Player;
use function str_ireplace;

class Tag {

	/** @var string */
	private string $name;

	protected \Closure $replaceCallback;

	/**
	 * Tag constructor.
	 * @param string $name
	 * @param \Closure $replaceCallback
	 */
	public function __construct(string $name, \Closure $replaceCallback ) {
		$this->name = $name;
		$this->replaceCallback = $replaceCallback;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	/**
	 * @param Player $player
	 * @param string $input
	 */
	public function replace(Player $player, string &$input): void {
		$output = ($this->replaceCallback)($player);
		if($output === null) return;
		$input = str_ireplace("{". $this->getName() . "}", $output, $input);
	}

}