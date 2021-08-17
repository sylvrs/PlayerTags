<?php

declare(strict_types=1);

namespace sys\jordan\tags;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use sys\jordan\tags\utils\PlayerTagsBaseTrait;

class PlayerTagsListener implements Listener {
	use PlayerTagsBaseTrait;

	public function __construct(PlayerTagsBase $plugin) {
		$this->setPlugin($plugin);
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
		$plugin->getLogger()->info(TextFormat::GREEN . "Successfully enabled listener!");
	}

	/**
	 * @param PlayerQuitEvent $event
	 *
	 * @priority LOWEST
	 */
	public function handleQuit(PlayerQuitEvent $event): void {
		$this->getPlugin()->getSessionManager()->delete($event->getPlayer());
	}

	/**
	 * @param PlayerJoinEvent $event
	 *
	 * @priority LOWEST
	 * @throws \Exception
	 */
	public function handleJoin(PlayerJoinEvent $event): void {
		$player = $event->getPlayer();
		$this->getPlugin()->getSessionManager()->create($player);
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
			$this->getPlugin()->getSessionManager()->createSessionData(UUID::fromString($packet->clientUUID), $packet->clientData);
		} else if(($packet instanceof InventoryTransactionPacket && $packet->trData instanceof UseItemOnEntityTransactionData) ||
				($packet instanceof LevelSoundEventPacket && $packet->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE)) {
			$session = $this->getPlugin()->getSessionManager()->fetch($player);
			$session->addClick();
		}
	}

}