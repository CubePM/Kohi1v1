<?php

namespace SavionLegends\Kohi1v1\commands;

use pocketmine\command\CommandMap;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use SavionLegends\Kohi1v1\Main;
use SavionLegends\Kohi1v1\utils\Utils;


class CommandClass extends Command{

    private $plugin, $server, $utils;


    /**
     * CommandClass constructor.
     * @param Main $plugin
     * @param $name
     * @param $desc
     * @param string $usage
     * @param array $aliases
     */
    public function __construct(Main $plugin, $name, $desc, string $usage, $aliases = []){
        parent::__construct($name, $desc, $usage, (array)$aliases);
        $this->plugin = $plugin;
        $this->server = $plugin->getServer();
        $this->utils = $plugin->utils;
    }

    /**
     * @return Main
     */
    public function getPlugin(): Main{
        return $this->plugin;
    }

    /**
     * @return \pocketmine\Server
     */
    public function getServer(): \pocketmine\Server{
        return $this->server;
    }

    /**
     * @return Utils
     */
    public function getUtils(): Utils{
        return $this->utils;
    }

    /**
     * @param Main $main
     * @param CommandMap $map
     */
    public static function registerAll(Main $main, CommandMap $map){
        $map->registerAll("kohi1v1", [
            new KohiCommand($main, "kohi", "Kohi1v1 main command!", "/kohi [create]")]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool|mixed
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(parent::testPermission($sender) === false){
            return false;
        }
        return true;
    }
}