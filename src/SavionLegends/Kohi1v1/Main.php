<?php

namespace SavionLegends\Kohi1v1;

use FormAPI\FormAPI;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\commands\CommandClass;
use SavionLegends\Kohi1v1\events\EventListener;
use SavionLegends\Kohi1v1\utils\Utils;


class Main extends PluginBase{

    /* @var \pocketmine\utils\Config*/
    public $matchesConfig;

    /* @var Utils*/
    public $utils;

    /* @var \SavionLegends\Kohi1v1\Main*/
    private static $instance;

    public function onLoad(){
        self::$instance = $this;
        @mkdir($this->getDataFolder());
        $this->utils = new Utils($this);
    }

    public function onEnable(){
        $this->matchesConfig = new Config($this->getDataFolder()."matches.yml", Config::YAML, ["Kohi1v1" => []]);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this, $this->utils), $this);

        FormAPI::register($this);
        Utils::registerMatches();
        CommandClass::registerAll($this, $this->getServer()->getCommandMap());

        $this->getLogger()->info(TextFormat::GREEN."Enabled!");
    }

    public function onDisable(){

    }

    /**
     * @return Main
     */
    public static function getInstance(): Main{
        return self::$instance;
    }
}