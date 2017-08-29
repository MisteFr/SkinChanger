<?php

namespace Miste\Exemple\Task;

use pocketmine\Server;
use pocketmine\scheduler\PluginTask;
use Miste\Exemple\Main;

class SkinChangeTask extends PluginTask{

    private $pg;

	public function __construct(Main $pg){
		parent::__construct($pg);
		$this->pg = $pg;
	}

    public function onRun(int $currentTick){
         foreach($this->pg->isChanging as $name => $data){
             $p = $this->pg->getServer()->getPlayer($name);
             if($p !== null){
                 $rand = $this->pg->id;
                 ++$this->pg->id;
                 $p->setSkin(base64_decode($this->pg->skins[$rand]["skindata"]), $this->pg->skins[$rand]["skinid"]);
                 $p->despawnFromAll();
                 $p->spawnToAll();
                 $p->sendMessage("§eYour skin was changed to §6".$this->pg->skins[$rand]["skinname"]."");
                 $this->pg->getLogger()->debug("Updating skin of ".$name." by SkinChangeTask.");
             }
         }
    }
}
