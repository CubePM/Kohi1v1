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
        $matchesConfig = $this->getPlugin()->matchesConfig->getAll();
        if(!$this->testPermission($sender)){
            return false;
        }

        if(isset($args[0]) && strtolower($args[0]) === "remove"){
            if(isset($args[1]) && is_string($args[1]) or is_int($args[1])){
                if(!isset($matchesConfig["Kohi1v1"][$args[1]])){
                    $sender->sendMessage(TextFormat::RED."That game doesn't exist!");
                    return false;
                }
                $matchesConfig["Kohi1v1"][$args[1]] = [];
                unset($matchesConfig["Kohi1v1"][$args[1]]);
                $this->getPlugin()->matchesConfig->setAll($matchesConfig);
                $this->getPlugin()->matchesConfig->save();
                $this->getPlugin()->matchesConfig->reload();
                if(count($matchesConfig["Kohi1v1"]) === 0){
                    $matchesConfig["Kohi1v1"] = [];
                    $this->getPlugin()->matchesConfig->setAll($matchesConfig);
                    $this->getPlugin()->matchesConfig->save();
                    $this->getPlugin()->matchesConfig->reload();
                }
                if(isset(Utils::$matches[$args[1]])){
                    unset(Utils::$matches[$args[1]]);
                }
                $sender->sendMessage(TextFormat::YELLOW."Removed game ".$args[1]."!");
            }
            return true;
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
            if($sender->hasPermission("kohi.command.main.create")) {
                $sender->sendMessage(TextFormat::YELLOW."Please select lobby position!");
                $this->getUtils()->isSetting[$sender->getName()] = [];
                $this->getUtils()->isSetting[$sender->getName()]["int"] = 0;
            }else{
                $sender->sendMessage(TextFormat::RED."You don't have permission to use that command.");
            }
            return true;
        }

        if(isset($args[0]) && strtolower($args[0]) === "join"){
            if($sender->hasPermission("kohi.command.main.join")){
                $this->getUtils()->sendGameForm($sender);
            }
            return true;
        }

        return true;
    }


}