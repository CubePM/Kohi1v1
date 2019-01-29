<?php

namespace SavionLegends\Kohi1v1\events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\Main;
use SavionLegends\Kohi1v1\utils\Utils;

class EventListener implements Listener{

    private $plugin, $utils;

    /**
     * EventListener constructor.
     * @param Main $plugin
     * @param Utils $utils
     */
    public function __construct(Main $plugin, Utils $utils){
        $this->plugin = $plugin;
        $this->utils = $utils;
    }

    /**
     * @return Main
     */
    public function getPlugin(): Main{
        return $this->plugin;
    }

    /**
     * @return Utils
     */
    public function getUtils(): Utils{
        return $this->utils;
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $username = $player->getName();
        $block = $event->getBlock();
        if(isset($this->getUtils()->isSetting[$username])){
            $i = $this->getUtils()->isSetting[$username]["int"];
            switch($this->getUtils()->isSetting[$username]["int"]){
                case 0:
                    $this->getUtils()->isSetting[$username]["lobby"] = ["x" => $block->x,
                    "y" => $block->y,
                    "z" => $block->z,
                    "level" => $block->getLevel()->getName()];
                        $this->getUtils()->isSetting[$username]["int"]++;
                        $player->sendMessage(TextFormat::YELLOW."Lobby set please set the first position!");
                        break;
                case 1:
                    $this->getUtils()->isSetting[$username]["pos1"] = ["x" => $block->x,
                        "y" => $block->y,
                        "z" => $block->z,
                        "level" => $block->getLevel()->getName()];
                    $this->getUtils()->isSetting[$username]["int"]++;
                    $player->sendMessage(TextFormat::YELLOW."Position #".($i)." set please select the next!");
                    break;
                case 2:
                    $this->getUtils()->isSetting[$username]["pos2"] = ["x" => $block->x,
                        "y" => $block->y,
                        "z" => $block->z,
                        "level" => $block->getLevel()->getName()];
                    $this->getUtils()->isSetting[$username]["int"]++;
                    $lobby = $this->getUtils()->isSetting[$username]["lobby"];
                    $pos1 = $this->getUtils()->isSetting[$username]["pos1"];
                    $pos2 = $this->getUtils()->isSetting[$username]["pos2"];
                    $int = $this->getUtils()->newMatch($this->getPlugin(), $lobby, $pos1, $pos2);
                    $player->sendMessage(TextFormat::YELLOW."Position #".($i)." set! All done match #".$int." created!");
                    unset($this->getUtils()->isSetting[$username]);
                    break;
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onHit(EntityDamageEvent $event){
        $player = $event->getEntity();
        if($player instanceof Player){
            if($event instanceof EntityDamageByEntityEvent){
                $damager = $event->getDamager();
                if($damager instanceof Player){
                    if($this->getUtils()->getGame($damager) !== null && $this->getUtils()->getGame($player) !== null){
                        if($event->getCause() !== EntityDamageEvent::CAUSE_ENTITY_ATTACK or $event->getCause() !== EntityDamageEvent::CAUSE_PROJECTILE){
                            $event->setCancelled(true);
                        }
                        if($player->getHealth() - $event->getFinalDamage() <= 0){
                            $event->setCancelled(true);
                            $match = $this->getUtils()->getGame($damager);
                            if($match->getName() !== $this->getUtils()->getGame($player)->getName()){
                                $this->getPlugin()->getLogger()->error("A problem with match winning has occurred!");
                                $match->end();
                                $this->getUtils()->getGame($player)->end();
                                return;
                            }
                            $match->win($damager);
                        }
                    }
                }
            }
        }
    }
}
