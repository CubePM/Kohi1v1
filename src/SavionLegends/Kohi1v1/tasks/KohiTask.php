<?php

namespace SavionLegends\Kohi1v1\tasks;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\utils\Utils;
use SavionLegends\Kohi1v1\Main;
use SavionLegends\Kohi1v1\game\GameClass;

class KohiTask extends Task{
    private $plugin, $utils, $server, $match, $tempTime;

    /**
     * KohiTask constructor.
     * @param Main $plugin
     * @param Utils $utils
     * @param GameClass $match
     */
    public function __construct(Main $plugin, Utils $utils, GameClass $match){
        $this->plugin = $plugin;
        $this->utils = $utils;
        $this->server = $plugin->getServer();
        $this->match = $match;
        $this->tempTime = 60;
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

    /**
     * @return \pocketmine\Server
     */
    public function getServer(): \pocketmine\Server{
        return $this->server;
    }

    /**
     * @return GameClass
     */
    public function getMatch(): GameClass{
        return $this->match;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick){
        $match = $this->getMatch();
        if(count($match->getPlayers()) === 0 && $match->getStatus() === GameClass::WAITING){
            $match->end();
            return;
        }

        if(count($match->getPlayers()) === 1 && $match->getStatus() === GameClass::STARTED){
            $keys = array_keys($match->getPlayers());
            $player = $this->getServer()->getPlayer($keys[0]);
            $match->win($player);
            return;
        }

        if(count($match->getPlayers()) === 0 && $match->getStatus() === GameClass::PREPARING){
            $match->end();
            return;
        }

        if(count($match->getPlayers()) === 1 && $match->getStatus() === GameClass::PREPARING){
            $keys = array_keys($match->getPlayers());
            $player = $this->getServer()->getPlayer($keys[0]);
            $player->sendMessage(TextFormat::RED."Match ending due to players have left the game!");
            $match->end();
            return;
        }

        if($match->getStatus() === GameClass::WAITING){
            if(count($match->getPlayers()) === 0){
                $match->end();
            }

            if(count($match->getPlayers()) === 2){
                $match->start();
            }

            if(count($match->getPlayers()) >= 2){
                $this->tempTime--;
                if($this->tempTime === 0){
                    $match->setStatus(GameClass::PREPARING);
                    $this->tempTime = 30;
                }
            }

            foreach($match->getPlayers() as $name){
                if(count($match->getPlayers()) === 0){
                    return;
                }

                $player = $this->getServer()->getPlayer($name);
                if($player === null or is_null($player)){
                    if(count($match->getPlayers()) >= 1) $match->end();
                    return;
                }

                $player->sendTip(TextFormat::YELLOW."Waiting for more players....[".count($match->getPlayers())."/2] Starting in: ".gmdate("i:s",$this->tempTime));
            }
        }elseif($match->getStatus() === GameClass::STARTED){
            $match->setTime(($match->getTime()-1));
            foreach($match->getPlayers() as $name){
                $player = $this->getServer()->getPlayer($name);
                $player->sendTip(TextFormat::YELLOW."Match time: ".gmdate("i:s", $match->getTime()));
            }
        }elseif($match->getStatus() === GameClass::PREPARING){
            $this->tempTime--;

            if(count($match->getPlayers()) === 2){
                $match->start();
            }

            if($this->tempTime === 0){
                $match->start();
            }
            foreach($match->getPlayers() as $name){
                $player = $this->getServer()->getPlayer($name);
                if($player === null or is_null($player)){
                    if(count($match->getPlayers()) >= 1) $match->end();
                    return;
                }
                $player->sendTip(TextFormat::YELLOW."Preparing: ".gmdate("i:s",$this->tempTime));
            }
        }
    }
}
