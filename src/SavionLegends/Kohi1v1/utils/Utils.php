<?php

namespace SavionLegends\Kohi1v1\utils;

use FormAPI\FormAPI;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\game\GameClass;
use SavionLegends\Kohi1v1\game\KohiClass;
use SavionLegends\Kohi1v1\Main;

class Utils{

    private $plugin;

    private static $instance;

    public static $matches = [];
    public static $tasks = [];

    public $inGame = [];

    /**
     * Utils constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin){
        self::$instance = $this;
        $this->plugin = $plugin;
    }

    /**
     * @return Utils
     */
    public static function getInstance(): Utils{
        return self::$instance;
    }

    /**
     * @return Main
     */
    public function getPlugin(): Main{
        return $this->plugin;
    }

    /**
     * @param Player $player
     * @return GameClass
     */
    public function getGame(Player $player): GameClass{
        return isset($this->inGame[$player->getName()]) ? $this->inGame[$player->getName()] : null;
    }

    public static function registerMatches(){
        foreach(Main::getInstance()->matchesConfig->get("Kohi1v1") as $match){
            self::$matches[$match["Name"]] = new KohiClass(Main::getInstance(), self::getInstance(), $match["Name"]);//TODO
        }
    }

    /**
     * @param Player $player
     */
    public function sendGameForm(Player $player){
        $form = FormAPI::createSimpleForm([$this, "handleGameForm"]);
        $form->setTitle("Kohi1v1");
        $form->addButton("Join");
        $form->sendToPlayer($player);
    }

    /**
     * @param Player $player
     * @param $response
     */
    public function handleGameForm(Player $player, $response): void{
        echo var_dump($response);
        if(!isset($response[0])){
            return;
        }
        $this->joinGame($player);
    }

    /**
     * @param Player $player
     */
    public function joinGame(Player $player){
        if(count(self::$matches) === 0){
            $player->sendMessage(TextFormat::RED."No matches available!");
            return;
        }

        $match = self::$matches[rand(1, count(self::$matches))];
        if($match instanceof KohiClass){
            if(count($match->getPlayers()) === 2){
                $player->sendMessage(TextFormat::RED."Match #".$match->getName()." is full try again for another match!");
                return;
            }
            if(!$match->isJoinable()){
                $player->sendMessage(TextFormat::RED."Match is not available try again later!");
                return;
            }
            if($match->getStatus() !== GameClass::WAITING){
                $player->sendMessage(TextFormat::RED."Match is not available try again later!");
                return;
            }
            if(isset($this->inGame[$player->getName()])){
                $player->sendMessage(TextFormat::RED."You already are in a game!");
                return;
            }
            $player->sendMessage(":P");
        }
    }

    /**
     * @param $key
     * @return string
     */
    public function translateColors($key): string{
        $message = $key;
        $message = str_replace("{BLACK}", TextFormat::BLACK, $message);
        $message = str_replace("{DARK_BLUE}", TextFormat::DARK_BLUE, $message);
        $message = str_replace("{DARK_GREEN}", TextFormat::DARK_GREEN, $message);
        $message = str_replace("{DARK_AQUA}", TextFormat::DARK_AQUA, $message);
        $message = str_replace("{DARK_RED}", TextFormat::DARK_RED, $message);
        $message = str_replace("{DARK_PURPLE}", TextFormat::DARK_PURPLE, $message);
        $message = str_replace("{ORANGE}", TextFormat::GOLD, $message);
        $message = str_replace("{GRAY}", TextFormat::GRAY, $message);
        $message = str_replace("{DARK_GRAY}", TextFormat::DARK_GRAY, $message);
        $message = str_replace("{BLUE}", TextFormat::BLUE, $message);
        $message = str_replace("{GREEN}", TextFormat::GREEN, $message);
        $message = str_replace("{AQUA}", TextFormat::AQUA, $message);
        $message = str_replace("{RED}", TextFormat::RED, $message);
        $message = str_replace("{LIGHT_PURPLE}", TextFormat::LIGHT_PURPLE, $message);
        $message = str_replace("{YELLOW}", TextFormat::YELLOW, $message);
        $message = str_replace("{WHITE}", TextFormat::WHITE, $message);
        $message = str_replace("{OBFUSCATED}", TextFormat::OBFUSCATED, $message);
        $message = str_replace("{BOLD}", TextFormat::BOLD, $message);
        $message = str_replace("{STRIKETHROUGH}", TextFormat::STRIKETHROUGH, $message);
        $message = str_replace("{UNDERLINE}", TextFormat::UNDERLINE, $message);
        $message = str_replace("{ITALIC}", TextFormat::ITALIC, $message);
        $message = str_replace("{RESET}", TextFormat::RESET, $message);
        return $message;
    }
}
