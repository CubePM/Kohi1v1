<?php

namespace SavionLegends\Kohi1v1;

use FormAPI\FormAPI;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\events\EventListener;
use SavionLegends\Kohi1v1\utils\Utils;


class Main extends PluginBase{

    /* @var \pocketmine\utils\Config*/
    public $signConfig;

    /* @var Utils*/
    private $utils;

    public function onLoad(){
        @mkdir($this->getDataFolder());
        $this->utils = new Utils($this);
    }

    public function onEnable(){
        $this->saveDefaultConfig();
        $this->signConfig = new Config($this->getDataFolder()."signs.yml", Config::YAML, []);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this, $this->utils), $this);
        FormAPI::register($this);

        $this->getLogger()->info(TextFormat::GREEN."Enabled!");
    }

    public function onDisable(){

    }
}