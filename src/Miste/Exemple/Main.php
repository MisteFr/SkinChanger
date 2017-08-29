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

}
