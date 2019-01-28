<?php

namespace SavionLegends\Kohi1v1\game;

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\Main;
use SavionLegends\Kohi1v1\utils\Utils;

class KohiClass extends GameClass{

    private $plugin, $utils, $server, $name, $status, $time, $players, $joinable;

    /**
     * KohiClass constructor.
     * @param Main $plugin
     * @param Utils $utils
     * @param $matchName
     */
    public function __construct(Main $plugin, Utils $utils, $matchName){
        $this->plugin = $plugin;
        $this->utils = $utils;
        $this->server = $plugin->getServer();
        $this->players = [];
        $this->name = $matchName;
        $this->status = GameClass::WAITING;
        $this->time = 600;
        $this->joinable = true;
    }

    /**
     * @return Main
     */
    public function getPlugin(){
        return $this->plugin;
    }

    /**
     * @return Utils
     */
    public function getUtils(): Utils{
        return $this->utils;
    }

    /**
     * @return \pocketmine\Server
     */
    public function getServer(){
        return $this->server;
    }

    /**
     * @return bool|mixed
     */
    public function isJoinable(){
        return $this->joinable ?? true;
    }

    /**
     * @param $bool
     */
    public function setJoinable($bool){
        $this->joinable = $bool;
    }

    /**
     * @return array
     */
    public function getPlayers(){
        return $this->players;
    }

    /**
     * @return string
     */
    public function getName(): string{
        return $this->name;
    }


    /**
     * @return int|mixed
     */
    public function getTime(){
        return $this->time;
    }

    /**
     * @param int $time
     * @return mixed|void
     */
    public function setTime(int $time){
        $this->time = $time;
    }

    /**
     * @return int|mixed
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * @param $status
     * @return mixed|void
     */
    public function setStatus($status){
        $this->status = $status;
    }

    /**
     * @param Player $player
     * @return mixed|void
     */
    public function addPlayer(Player $player){
        $this->players[$player->getName()] = $player->getName();
        $this->getUtils()->inGame[$player->getName()] = $this;

        $player->sendMessage(TextFormat::YELLOW."Joined match #".$this->getName()."!");
    }

    /**
     * @param Player $player
     * @return mixed|void
     */
    public function removePlayer(Player $player){
        if($player->isOnline()){
            $player->setGamemode(Player::SURVIVAL);
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->removeAllEffects();
            $player->setMaxHealth(20);
            $player->setHealth(20);
            $player->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
        }

        if(isset($this->players[$player->getName()])) unset($this->players[$player->getName()]);

        if(isset($this->getUtils()->inGame[$player->getName()])) unset($this->getUtils()->inGame[$player->getName()]);


        if(count($this->getPlayers()) === 0 && $this->getStatus() === GameClass::WAITING){
            $this->end();
            return;
        }

        if(count($this->getPlayers()) === 1 && $this->getStatus() === GameClass::STARTED){
            $keys = array_keys($this->players);
            $player = $this->getServer()->getPlayer($keys[0]);
            $this->win($player);
            return;
        }

        if(count($this->getPlayers()) === 0 && $this->getStatus() === GameClass::PREPARING){
            $this->end();
            return;
        }

        if(count($this->getPlayers()) === 1 && $this->getStatus() === GameClass::PREPARING){
            $keys = array_keys($this->players);
            $player = $this->getServer()->getPlayer($keys[0]);
            $player->sendMessage(TextFormat::RED."Match ending due to players have left the game!");
            $this->end();
            return;
        }
    }

    public function win(Player $player){
        //TODO
        $this->end();
    }

    public function start(){
        $this->status = self::STARTED;
        $this->time = 600;
        foreach($this->getPlayers() as $name){
            $player = $this->getServer()->getPlayer($name);
            $player->sendMessage(TextFormat::YELLOW."Match has started!");
        }
    }

    public function end(){
        if(isset(Utils::$tasks[$this->getName()])){
            $this->getPlugin()->getScheduler()->cancelTask(Utils::$tasks[$this->getName()]);
            unset(Utils::$tasks[$this->getName()]);
            if(count($this->getPlayers()) !== 0){
                foreach($this->getPlayers() as $name){
                    $player = $this->getServer()->getPlayer($name);
                    $player->getInventory()->clearAll();
                    $player->setHealth($player->getMaxHealth());
                    $this->removePlayer($player);
                }
            }
        }
        $this->status = GameClass::WAITING;
        $this->time = 600;
        $this->players = [];
    }

}