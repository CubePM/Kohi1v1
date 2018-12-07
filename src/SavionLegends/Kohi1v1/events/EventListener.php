<?php

namespace SavionLegends\Kohi1v1\events;


use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\tile\Sign;
use SavionLegends\Kohi1v1\Main;

class EventListener implements Listener{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @return Main
     */
    public function getPlugin(): Main{
        return $this->plugin;
    }

    public function onSignChange(SignChangeEvent $event){
        $player = $event->getPlayer();
        $sign = $event->getBlock();
        if($sign instanceof Sign){
            if($player->hasPermission("kohi1v1.sign.set") && $event->getLine(0) === $this->getPlugin()->getConfig()->get("Sign-line-activate")){
                $event->setLine(0, $this->getPlugin()->getUtils()->translateColors($this->getPlugin()->getConfig()->get("Sign-line-activate")));
                $event->setLine(1, "Players: 0/0");
                $event->setLine(2, "Waiting");
            }
        }
    }
}
