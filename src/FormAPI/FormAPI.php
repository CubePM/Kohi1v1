<?php

namespace FormAPI;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class FormAPI extends PluginBase implements Listener {

	/** @var int */
	public static $formCount = 0;
	/** @var array */
	public static $forms = [];

    public static function register(PluginBase $plugin): void{
        Server::getInstance()->getPluginManager()->registerEvents(new EventListener, $plugin);
    }

	/**
	 * @param callable $function
	 * @return CustomForm
	 */
	public static function createCustomForm(callable $function = null): CustomForm{
		self::$formCount++;
		$form = new CustomForm(self::$formCount, $function);
		if($function !== null){
			self::$forms[self::$formCount] = $form;
		}
		return $form;
	}

    /**
     * @param callable|null $function
     * @return SimpleForm
     */
    public static function createSimpleForm(callable $function = null): SimpleForm{
		self::$formCount++;
		$form = new SimpleForm(self::$formCount, $function);
		if($function !== null){
			self::$forms[self::$formCount] = $form;
		}
		return $form;
	}
}
