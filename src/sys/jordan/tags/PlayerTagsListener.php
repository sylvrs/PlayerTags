<?php

declare(strict_types=1);

namespace sys\jordan\tags;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use sys\jordan\tags\session\PlayerSession;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;

class PlayerTagsListener implements Listener {
	use PlayerTagsBaseTrait;

	/**
	 * PlayerTagsListener constructor.
	 * @param PlayerTagsBase $plugin
	 */
	public function __construct(PlayerTagsBase $plugin) {
		$this->setPlugin($plugin);
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
		$plugin->getLogger()->info(TextFormat::GREEN . "Successfully enabled listener!");
	}

	/**
	 * @param PlayerPreLoginEvent $event
	 *
	 * @priority LOWEST
	 */
	public function handlePlayerPreLogin(PlayerPreLoginEvent $event): void {
		$player = $event->getPlayer();
		$session = $this->getPlugin()->getSessionManager()->find($player);
		if($session instanceof PlayerSession) {
			$session->setPlayer($event->getPlayer());
			$session->start($this->getPlugin());
		}
	}

	/**
	 * @param PlayerQuitEvent $event
	 *
	 * @priority LOWEST
	 */
	public function handleQuit(PlayerQuitEvent $event): void {
		$this->getPlugin()->getSessionManager()->remove($event->getPlayer());
		$this->getPlugin()->getLogger()->debug("Destroying player session");
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 *
	 * @priority LOWEST
	 */
	public function handleDataPacketReceive(DataPacketReceiveEvent $event): void {
		$player = $event->getPlayer();
		$packet = $event->getPacket();
		if($packet instanceof LoginPacket) {
			$session = $this->getPlugin()->getSessionManager()->create(UUID::fromString($packet->clientUUID));
			$session->setDevice($packet->clientData["DeviceModel"]);
			$session->setInputMode($packet->clientData["CurrentInputMode"]);
			$session->setOS($packet->clientData["DeviceOS"]);
			$this->getPlugin()->getLogger()->debug("Creating player session");
		} elseif($packet instanceof InventoryTransactionPacket && $packet->trData instanceof UseItemOnEntityTransactionData) {
			$this->getPlugin()->getSessionManager()->find($player)->addClick();
		} elseif($packet instanceof LevelSoundEventPacket) {
			if($packet->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE) {
				$this->getPlugin()->getSessionManager()->find($player)->addClick();
			}
		}
	}

}