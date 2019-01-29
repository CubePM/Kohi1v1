<?php

namespace SavionLegends\Kohi1v1\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
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
}
