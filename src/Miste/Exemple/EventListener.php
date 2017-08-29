<?php

namespace Miste\Exemple;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use Miste\Exemple\Main;

class EventListener implements Listener {

    private $pg;
    
	public function __construct(Main $pg) {
		$this->pg = $pg;
    }
    
    public function onQuit(PlayerQuitEvent $ev){
        if(isset($this->pg->isChanging[$ev->getPlayer()->getName()])){
            unset($this->pg->isChanging[$ev->getPlayer()->getName()]);
            $this->pg->getLogger()->debug("Unsetting ".$ev->getPlayer()->getName()." from the skinChanger by PlayerQuitEvent.");
        }
    }
}
