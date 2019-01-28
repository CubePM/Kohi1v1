<?php

namespace FormAPI;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;

class SimpleForm extends Form implements \JsonSerializable{

	const IMAGE_TYPE_PATH = 0;
	const IMAGE_TYPE_URL = 1;

	/** @var int */
	public $id;
	/** @var array */
	private $data = [];
	/** @var string */
	private $content = "";
	/** @var string */
	public $playerName;

	/**
	 * @param int $id
	 * @param callable $callable
	 */
	public function __construct(int $id, ?callable $callable){
		parent::__construct($id, $callable);
		$this->data["type"] = "form";
		$this->data["title"] = "";
		$this->data["content"] = $this->content;
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
		$player->dataPacket($pk);
		$this->playerName = $player->getName();
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title): void{
		$this->data["title"] = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string{
		return $this->data["title"];
	}

	/**
	 * @return string
	 */
	public function getContent(): string{
		return $this->data["content"];
	}

	/**
	 * @param string $content
	 */
	public function setContent(string $content): void{
		$this->data["content"] = $content;
	}

	/**
	 * @param string $text
	 * @param int $imageType
	 * @param string $imagePath
	 */
	public function addButton(string $text, int $imageType = -1, string $imagePath = ""): void{
        $content = ["text" => $text];
        if ($imageType !== -1) {
            $content["image"]["type"] = $imageType === 0 ? "path" : "url";
            $content["image"]["data"] = $imagePath;
        }
        $this->data["buttons"][] = $content;
    }

    final public function jsonSerialize(): array{
        $data = $this->data;
        unset($data["content"]);
        return $data;
    }
}
