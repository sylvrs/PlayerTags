<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag\group;


use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sys\jordan\tags\tag\Tag;
use sys\jordan\tags\tag\TagFactory;
use function round;
use function str_repeat;
use function substr_replace;

class DefaultTagGroup extends TagGroup {

	/**
	 * @param TagFactory $factory
	 * @return Tag[]
	 */
	public function load(TagFactory $factory): array {
		return [
			new Tag("x", function (Player $player): string {
				return (string) $player->getPosition()->getFloorX();
			}),
			new Tag("y", function (Player $player): string {
				return (string) $player->getPosition()->getFloorY();
			}),
			new Tag("z", function (Player $player): string {
				return (string) $player->getPosition()->getFloorZ();
			}),
			new Tag("world", function (Player $player): string {
				return $player->getLocation()->isValid() ? $player->getWorld()->getDisplayName() : "unknown";
			}),
			new Tag("item_id", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getId();
			}),
			new Tag("item_damage", function (Player $player): string {
				$item = $player->getInventory()->getItemInHand();
				return (string) ($item instanceof Durable ? $item->getDamage() : $item->getMeta());
			}),
			new Tag("item_count", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getCount();
			}),
			new Tag("item_name", function (Player $player): string {
				return $player->getInventory()->getItemInHand()->getName();
			}),
			new Tag("ip", function (Player $player): string {
				return $player->getNetworkSession()->getIp();
			}),
			new Tag("gamemode", function (Player $player): string {
				return $player->getGamemode()->getEnglishName();
			}),
			new Tag("ping", function (Player $player): string {
				return (string) $player->getNetworkSession()->getPing();
			}),
			new Tag("cps", function (Player $player): string {
				return (string) $this->getPlugin()->getSessionManager()->get($player)->getClicksPerSecond();
			}),
			new Tag("health", function (Player $player): string {
				return (string) round($player->getHealth(), 2);
			}),
			new Tag("max_health", function (Player $player): string {
				return (string) $player->getMaxHealth();
			}),
			new Tag("health_bar", function (Player $player): string {
				$healthString = str_repeat("|", $player->getMaxHealth());
				$effects = $player->getEffects();
				$color = match(true) {
					$effects->has(VanillaEffects::POISON()) => TextFormat::YELLOW,
					$effects->has(VanillaEffects::WITHER()) => TextFormat::LIGHT_PURPLE,
					default => TextFormat::GREEN
				};
				return $color . ($player->getHealth() < $player->getMaxHealth() ? (substr_replace($healthString, TextFormat::RED, (int) $player->getHealth() - 1, 0)) : $healthString) . ($player->getAbsorption() > 0 ? TextFormat::GOLD . str_repeat("|", (int) $player->getAbsorption()) : "");
			}),
			new Tag("device", function (Player $player): string {
				return $this->getPlugin()->getSessionManager()->get($player)->getDevice();
			}),
			new Tag("input_mode", function (Player $player): string{
				return $this->getPlugin()->getSessionManager()->get($player)->getInputModeString();
			}),
			new Tag("os", function (Player $player): string {
				return $this->getPlugin()->getSessionManager()->get($player)->getOSString();
			})
		];
	}
}