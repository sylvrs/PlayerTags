<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group;


use sys\jordan\tags\PlayerTagsBase;
use sys\jordan\tags\tag\Tag;
use sys\jordan\tags\tag\TagFactory;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;

abstract class TagGroup {
	use PlayerTagsBaseTrait;

	/**
	 * TagGroup constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		$this->setPlugin($plugin);
	}

	/**
	 * @param TagFactory $factory
	 * @return Tag[]
	 */
	abstract public function load(TagFactory $factory): array;

}