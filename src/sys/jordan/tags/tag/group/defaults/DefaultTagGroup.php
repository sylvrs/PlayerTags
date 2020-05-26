<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use sys\jordan\tags\tag\group\TagGroup;
use sys\jordan\tags\tag\Tag;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\Player;
use function round;

class DefaultTagGroup extends TagGroup {

	/**
	 * @param TagFactory $factory
	 * @return array
	 */
	public function load(TagFactory $factory): array {
		return [
			new Tag("x", function (Player $player): string {
				return (string) $player->getFloorX();
			}),
			new Tag("y", function (Player $player): string {
				return (string) $player->getFloorY();
			}),
			new Tag("z", function (Player $player): string {
				return (string) $player->getFloorZ();
			}),
			new Tag("level", function (Player $player): string {
				return $player->isValid() ? $player->getLevel()->getName() : "unknown";
			}),
			new Tag("itemId", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getId();
			}),
			new Tag("itemDamage", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getDamage();
			}),
			new Tag("itemName", function (Player $player): string {
				return $player->getInventory()->getItemInHand()->getName();
			}),
			new Tag("usage", function (Player $player): string {
				return (string) $this->getPlugin()->getServer()->getTickUsage();
			}),
			new Tag("ping", function (Player $player): string {
				return (string) $player->getPing();
			}),
			new Tag("cps", function (Player $player): string {
				return (string) $this->getPlugin()->getSessionManager()->findSession($player)->getClicksPerSecond();
			}),
			new Tag("health", function (Player $player): string {
				return (string) round($player->getHealth(), 2);
			}),
			new Tag("max_health", function (Player $player): string {
				return (string) $player->getMaxHealth();
			}),
			new Tag("device", function (Player $player): string {
				return $this->getPlugin()->getSessionManager()->findSession($player)->getDevice();
			}),
			new Tag("input_mode", function (Player $player): string{
				return $this->getPlugin()->getSessionManager()->findSession($player)->getInputModeString();
			}),
			new Tag("os", function (Player $player): string {
				return $this->getPlugin()->getSessionManager()->findSession($player)->getOSString();
			})
		];
	}
}