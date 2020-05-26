<?php

declare(strict_types=1);

namespace sys\jordan\tags\utils;


use sys\jordan\tags\PlayerTagsBase;

trait PlayerTagsBaseTrait {

	/** @var PlayerTagsBase */
	private $plugin;

	/**
	 * @return PlayerTagsBase
	 */
	public function getPlugin(): PlayerTagsBase {
		return $this->plugin;
	}

	/**
	 * @param PlayerTagsBase $plugin
	 */
	public function setPlugin(PlayerTagsBase $plugin): void {
		$this->plugin = $plugin;
	}
}