<?php

namespace FormAPI;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;

abstract class Form implements \JsonSerializable{

    /** @var int */
    public $id;
    /** @var array */
    private $data = [];
    /** @var string */
    public $playerName;
    /** @var callable */
    private $callable;

    /**
     * @param int $id
     * @param callable $callable
     */
    public function __construct(int $id, ?callable $callable){
        $this->id = $id;
        $this->callable = $callable;
    }

    /**
     * @return int
     */
    public function getId(): int{
        return $this->id;
    }

    /**
     * @param Player $player
     */
    public function sendToPlayer(Player $player): void{
        $pk = new ModalFormRequestPacket();
        $pk->formId = $this->id;
        $pk->formData = json_encode($this->data);
        $player->sendDataPacket($pk);
        $this->playerName = $player->getName();
    }

    public function isRecipient(Player $player): bool{
        return $player->getName() === $this->playerName;
    }

    public function getCallable(): ?callable{
        return $this->callable;
    }

    abstract public function jsonSerialize(): array;
}
