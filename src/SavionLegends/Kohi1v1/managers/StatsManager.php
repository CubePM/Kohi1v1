<?php

namespace SavionLegends\Kohi1v1\managers;

use pocketmine\Player;
use SavionLegends\Kohi1v1\Main;
use SavionLegends\Kohi1v1\utils\Utils;

class StatsManager{

    private $player, $plugin, $utils;

    public function __construct(Player $player, Main $plugin, Utils $utils){
        $this->player = $player;
        $this->plugin = $plugin;
        $this->utils = $utils;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player{
        return $this->player;
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

}