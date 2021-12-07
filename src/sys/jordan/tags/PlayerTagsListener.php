<?php

declare(strict_types=1);

namespace sys\jordan\tags;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
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
		$plugin->getLogger()->debug("Successfully enabled listener!");
	}

	/**
	 * @param PlayerJoinEvent $event
	 *
	 * @priority LOWEST
	 */
	public function handleJoin(PlayerJoinEvent $event): void {
		$player = $event->getPlayer();
		$this->getPlugin()->getLogger()->debug("Starting player session for {$player->getName()}...");
		$session = $this->getPlugin()->getSessionManager()->get($player);
		$session->start($this->getPlugin());
	}

	/**
	 * @param PlayerQuitEvent $event
	 *
	 * @priority LOWEST
	 */
	public function handleQuit(PlayerQuitEvent $event): void {
		$player = $event->getPlayer();
		$this->getPlugin()->getSessionManager()->remove($player);
		$this->getPlugin()->getLogger()->debug("Destroying player session for {$player->getName()}...");
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 *
	 * @priority LOWEST
	 */
	public function handleDataPacketReceive(DataPacketReceiveEvent $event): void {
		$packet = $event->getPacket();
		$player = $event->getOrigin()->getPlayer();
		if($player instanceof Player && $player->isOnline()) {
			$session = $this->getPlugin()->getSessionManager()->get($player);
			if($packet instanceof InventoryTransactionPacket && $packet->trData instanceof UseItemOnEntityTransactionData) {
				$session->addClick();
			} else if($packet instanceof LevelSoundEventPacket && $packet->sound === LevelSoundEvent::ATTACK_NODAMAGE) {
				$session->addClick();
			}
		}
	}

}