<?php

namespace sys\jordan\tags\tag\migration;

final class MigrationChecker {

	/**
	 * A dictionary of tags in key-value pairs (old -> new) that need to be migrated if found.
	 *
	 * This is mainly here to prevent the breaking of any tags on existing servers.
	 *
	 * @var array|string[]
	 */
	protected static array $MIGRATIONS = [
		"level" => "world",
	];

	public static function checkTag(string $tag): MigrationResult {
		$changed = [];
		$newTag = "";
		foreach(self::$MIGRATIONS as $old => $new) {
			if(preg_match_all("/\{($old)\}/mi", $tag, $matches)) {
				if(($changedCount = count($matches[0]) ?? 0) > 0) {
					$changed[$old] = [$newTag, $changedCount];
					$newTag = str_ireplace("{{$old}}", "{{$new}}", $tag);
				}
			}
		}
		return MigrationResult::create($changed, $newTag);
	}
}