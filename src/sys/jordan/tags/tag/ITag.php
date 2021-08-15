<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;

use pocketmine\player\Player;

interface ITag {

	public function replace(Player $player, string &$input): void;
}