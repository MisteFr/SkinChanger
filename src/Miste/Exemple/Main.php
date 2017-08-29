<?php

namespace Miste\Exemple;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;
use pocketmine\math\Vector3;
use pocketmine\utils\Utils;

use Miste\Exemple\Commands\SkinCommand;
use Miste\Exemple\Task\SkinChangeTask;

class Main extends PluginBase{

    public $isChanging = [];
    public $skins;
    public $id = 1;

    public function onEnable(){
        $this->saveDefaultConfig();
        $this->getLogger()->info("I have been enabled");
        $this->getLogger()->info("Loading skins of the resources folder...");
        $this->loadSkins();
        $this->getLogger()->info("Registering the listener...");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getLogger()->info("Registering the task...");
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new SkinChangeTask($this), 60);
        $this->getLogger()->info("Registering the command...");
        $this->getServer()->getCommandMap()->register("skin", new SkinCommand($this, "skin"));
        $this->getLogger()->info("Done ! You can now test this plugin by tapping /us on the chat !");
    }

    public function loadSkins(){
        @mkdir($this->getDataFolder());
        $this->skins = json_decode(file_get_contents($this->getDataFolder().'skins.json'), true);
    }

    public function decodePacket($packet){
			$packet->encode();
			var_dump("[Client -> Server 0x" . dechex($packet->pid()) . "] " . (new \ReflectionClass($packet))->getShortName() . " (length " . strlen($packet->buffer) . ")");
			var_dump($this->getFields($packet));
    }
    
    public function getFields($packet) : string{
		$output = "";
		foreach($packet as $key => $value){
			if($key === "buffer"){
				continue;
			}
			$output .= " $key: " . self::safePrint($value) . PHP_EOL;
		}
		return rtrim($output);
    }

    
    private static function safePrint($value, int $spaces = 2) : string{
		if(is_object($value)){
			if((new \ReflectionClass($value))->hasMethod("__toString")){
				$value = $value->__toString();
			}else{
				$value = get_class($value);
			}
		}elseif(is_string($value)){
			if($value === ""){
				$value = "(empty)";
			}elseif(preg_match('#([^\x20-\x7E])#', $value) > 0){
				$value = "0x" . bin2hex($value);
			}
		}elseif(is_array($value)){
			$d = "Array:";
			foreach($value as $key => $v){
				$d .= PHP_EOL . str_repeat(" ", $spaces) . "$key: " . self::safePrint($v, $spaces + 1);
			}
			$value = $d;
		}else{
			$value = trim(str_replace("\n", "\n ", print_r($value, true)));
		}
		return $value;
	}
}
