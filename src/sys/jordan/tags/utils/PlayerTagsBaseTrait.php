<?php

declare(strict_types=1);

namespace sys\jordan\tags\utils;


use sys\jordan\tags\PlayerTagsBase;

trait PlayerTagsBaseTrait {

	private PlayerTagsBase $plugin;

	public function getPlugin(): PlayerTagsBase {
		return $this->plugin;
	}

	public function setPlugin(PlayerTagsBase $plugin): void {
		$this->plugin = $plugin;
	}
}