<?php

namespace SavionLegends\Kohi1v1\game;

use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\Main;
use SavionLegends\Kohi1v1\utils\Utils;

class KohiClass extends GameClass{

    private $plugin, $utils, $server, $name, $status, $time, $players, $joinable, $positions, $tempPos;

    /**
     * KohiClass constructor.
     * @param Main $plugin
     * @param Utils $utils
     * @param $matchName
     * @param array $positions
     */
    public function __construct(Main $plugin, Utils $utils, $matchName, array $positions){
        $this->plugin = $plugin;
        $this->utils = $utils;
        $this->server = $plugin->getServer();
        $this->players = [];
        $this->name = $matchName;
        $this->status = GameClass::WAITING;
        $this->time = 600;
        $this->joinable = true;
        $this->positions = $positions;
        $this->tempPos = 0;
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

    /**
     * @return array
     */
    public function getPositions(): array{
        return $this->positions;
    }

    public function getNextPos(){
        $this->tempPos++;
        $positions = $this->getPositions();
        $x = $positions["pos".$this->tempPos]["x"];
        $y = $positions["pos".$this->tempPos]["y"];
        $z = $positions["pos".$this->tempPos]["z"];
        $level = $positions["pos".$this->tempPos]["level"];
        return new Position($x, $y, $z, $this->getServer()->getLevelByName($level));
    }

    public function win(Player $player){
        $player->sendMessage("You won on match #".$this->getName()."!");
        $this->end();
    }

    public function start(){
        $this->status = self::STARTED;
        $this->time = 600;
        foreach($this->getPlayers() as $name){
            $player = $this->getServer()->getPlayer($name);
            $player->sendMessage(TextFormat::YELLOW."Match has started!");

            $player->getArmorInventory()->clearAll();
            $player->getInventory()->clearAll();
            $player->setMaxHealth(20);
            $player->setHealth(20);

            $inventory = $player->getInventory();
            $armorInventory = $player->getArmorInventory();

            $armorInventory->setHelmet(Item::get(Item::DIAMOND_HELMET));
            $armorInventory->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
            $armorInventory->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
            $armorInventory->setBoots(Item::get(Item::DIAMOND_BOOTS));
            $armorInventory->sendContents($player);

            $inventory->addItem(Item::get(Item::DIAMOND_SWORD));
            $inventory->addItem(Item::get(Item::BOW));
            $inventory->addItem(Item::get(Item::ARROW, 0, 32));
            $inventory->addItem(Item::get(Item::SPLASH_POTION, Potion::HEALING, 32));
            $inventory->addItem(Item::get(Item::SPLASH_POTION, Potion::SWIFTNESS, 10));
            $inventory->sendContents($player);
            $player->teleport($this->getNextPos());
        }
    }

    public function end(){
        if(isset(Utils::$tasks[$this->getName()])){
            $this->getPlugin()->getScheduler()->cancelTask(Utils::$tasks[$this->getName()]);
            unset(Utils::$tasks[$this->getName()]);
        }
        if(count($this->getPlayers()) !== 0){
            foreach($this->getPlayers() as $name){
                $player = $this->getServer()->getPlayer($name);
                $player->getInventory()->clearAll();
                $player->setHealth($player->getMaxHealth());
                $this->removePlayer($player);
            }
        }
        $this->status = GameClass::WAITING;
        $this->time = 600;
        $this->players = [];
        $this->tempPos = 0;
    }

}