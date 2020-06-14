<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group\defaults;


use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use sys\jordan\tags\tag\group\TagGroup;
use sys\jordan\tags\tag\Tag;
use sys\jordan\tags\tag\TagFactory;
use pocketmine\Player;
use function round;
use function str_repeat;
use function substr_replace;

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
			new Tag("item_id", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getId();
			}),
			new Tag("item_damage", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getDamage();
			}),
			new Tag("item_count", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getCount();
			}),
			new Tag("item_name", function (Player $player): string {
				return $player->getInventory()->getItemInHand()->getName();
			}),
			new Tag("ip", function (Player $player): string {
				return $player->getAddress();
			}),
			new Tag("gamemode", function (Player $player): string {
				return Server::getGamemodeName($player->getGamemode());
			}),
			new Tag("ping", function (Player $player): string {
				return (string) $player->getPing();
			}),
			new Tag("cps", function (Player $player): string {
				return (string) $this->getPlugin()->getSessionManager()->find($player)->getClicksPerSecond();
			}),
			new Tag("health", function (Player $player): string {
				return (string) round($player->getHealth(), 2);
			}),
			new Tag("max_health", function (Player $player): string {
				return (string) $player->getMaxHealth();
			}),
			new Tag("health_bar", function (Player $player): string {
				$healthString = str_repeat("|", (int) $player->getMaxHealth());
				$color = TextFormat::GREEN;
				if($player->hasEffect(Effect::POISON)) {
					$color = TextFormat::YELLOW;
				} else if($player->hasEffect(Effect::WITHER)) {
					$color = TextFormat::LIGHT_PURPLE;
				}
				return $color . ($player->getHealth() < $player->getMaxHealth() ? (substr_replace($healthString, TextFormat::RED, (int) $player->getHealth() - 1, 0)) : $healthString) . ($player->getAbsorption() > 0 ? TextFormat::GOLD . str_repeat("|", (int) $player->getAbsorption()) : "");
			}),
			new Tag("device", function (Player $player): string {
				return $this->getPlugin()->getSessionManager()->find($player)->getDevice();
			}),
			new Tag("input_mode", function (Player $player): string{
				return $this->getPlugin()->getSessionManager()->find($player)->getInputModeString();
			}),
			new Tag("os", function (Player $player): string {
				return $this->getPlugin()->getSessionManager()->find($player)->getOSString();
			})
		];
	}
}