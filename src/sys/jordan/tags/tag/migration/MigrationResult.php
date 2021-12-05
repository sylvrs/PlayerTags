<?php

namespace sys\jordan\tags\tag\migration;

final class MigrationResult {

	/**
	 * @param array $changed - A dictionary of key-value pairs where the key is the old tag name and the value is the new tag name as well as the instances changed.
	 * @param string $newTag - The resulting tag name after the migration.
	 */
	public function __construct(
		public array $changed = [],
		public string $newTag = ""
	) {}


	public static function create(array $changed, string $newTag): self {
		return new self($changed, $newTag);
	}

	public function hasChanged(): bool {
		return count($this->changed) > 0;
	}
}