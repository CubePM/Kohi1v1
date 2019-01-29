<?php

namespace SavionLegends\Kohi1v1\game;

use pocketmine\Player;
use SavionLegends\Kohi1v1\Main;
use SavionLegends\Kohi1v1\utils\Utils;

abstract class GameClass{

    public const WAITING = 0;
    public const PREPARING = 1;
    public const STARTED = 2;

    /**
     * GameClass constructor.
     * @param Main $plugin
     * @param Utils $utils
     * @param $matchName
     * @param array $positions
     */
    abstract public function __construct(Main $plugin, Utils $utils, $matchName, array $positions);

    /**
     * @return array
     */
    abstract public function getPositions(): array;

    /**
     * @return mixed
     */
    abstract public function isJoinable();

    /**
     * @param $bool
     * @return mixed
     */
    abstract public function setJoinable($bool);

    /**
     * @param Player $player
     * @return mixed
     */
    abstract public function addPlayer(Player $player);

    /**
     * @return mixed
     */
    abstract public function getPlayers();

    /**
     * @param Player $player
     * @return mixed
     */
    abstract public function removePlayer(Player $player);

    /**
     * @return mixed
     */
    abstract public function getStatus();

    /**
     * @param $status
     * @return mixed
     */
    abstract public function setStatus($status);

    /**
     * @param int $time
     * @return mixed
     */
    abstract public function setTime(int $time);

    /**
     * @return mixed
     */
    abstract public function getTime();

    /**
     * @return mixed
     */
    abstract public function end();

    /**
     * @return mixed
     */
    abstract public function start();

    /**
     * @return mixed
     */
    abstract public function getName();

    /**
     * @param Player $player
     * @return mixed
     */
    abstract public function win(Player $player);
}
