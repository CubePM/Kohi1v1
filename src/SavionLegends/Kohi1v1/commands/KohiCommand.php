<?php


namespace SavionLegends\Kohi1v1\commands;


use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\Main;
use SavionLegends\Kohi1v1\utils\Utils;

class KohiCommand extends CommandClass{

    /**
     * KohiCommand constructor.
     * @param Main $plugin
     * @param $name
     * @param $desc
     * @param string $usage
     * @param array $aliases
     */
    public function __construct(Main $plugin, $name, $desc, string $usage, array $aliases = []){
        parent::__construct($plugin, $name, $desc, $usage, $aliases);
        $this->setPermission("kohi.command.main");
    }

    /**
     * @return Main
     */
    public function getPlugin(): Main{
        return parent::getPlugin();
    }

    /**
     * @return \pocketmine\Server
     */
    public function getServer(): \pocketmine\Server{
        return parent::getServer();
    }

    /**
     * @return Utils
     */
    public function getUtils(): Utils{
        return parent::getUtils();
    }


    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool|mixed
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args){
       if(!$this->testPermission($sender)){
           return false;
       }
       if(!$sender instanceof Player){
           $sender->sendMessage(TextFormat::RED."Please join the server to run commands!");
           return false;
       }
       if(!isset($args[0])){
           $sender->sendMessage($this->getUsage());
           return false;
       }
       if(isset($args[0]) && strtolower($args[0]) === "create"){
           if(isset($this->getUtils()->isSetting[$sender->getName()])){
               $sender->sendMessage(TextFormat::RED."You already are setting a game!");
               return false;
           }
           if($sender->hasPermission("kohi.command.main.create")){
               $sender->sendMessage(TextFormat::YELLOW."Please select lobby position!");
               $this->getUtils()->isSetting[$sender->getName()] = [];
               $this->getUtils()->isSetting[$sender->getName()]["int"] = 0;
           }else{
               $sender->sendMessage(TextFormat::RED."You don't have permission to use that command.");
           }
       }
        if(isset($args[0]) && strtolower($args[0]) === "remove"){
            //TODO
        }
       return true;
    }

}