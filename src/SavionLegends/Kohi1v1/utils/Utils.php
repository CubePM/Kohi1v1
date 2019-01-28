<?php

namespace SavionLegends\Kohi1v1\utils;

use FormAPI\FormAPI;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use SavionLegends\Kohi1v1\game\KohiClass;
use SavionLegends\Kohi1v1\Main;

class Utils{

    private $plugin;

    public static $matches = [];

    /**
     * Utils constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @return Main
     */
    public function getPlugin(): Main{
        return $this->plugin;
    }

    public static function registerMatches(){
        foreach(Main::getInstance()->matchesConfig->get("Kohi1v1") as $match){
            self::$matches[$match["Name"]] = new KohiClass();//TODO
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

        }
    }
}
