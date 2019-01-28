<?php

namespace SavionLegends\Kohi1v1\events;

use pocketmine\event\Listener;
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

}
