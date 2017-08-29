<?php

namespace Miste\Exemple\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use Miste\Exemple\Main;
use pocketmine\Player;

class SkinCommand extends PluginCommand {

    private $pg;

	public function __construct(Main $pg, $name) {
		parent::__construct($name, $pg);
		$this->pg = $pg;
    }
    
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
      if(isset($args[0])){
        switch($args[0]){
            case "start":
                if(!isset($this->pg->isChanging[$sender->getName()])){
                    $this->pg->isChanging[$sender->getName()] = [$sender->getSkinData(), $sender->getSkinId()];
                    $sender->sendMessage("[Exemple] Your skin is now changing every 3 seconds, enjoy !");
                }else{
                    $sender->sendMessage("[Exemple] Your skin is already changing every 3 seconds, you can stop it by tapping /skin stop in the chat !");
                }
            return true;
            break;
            case "stop":
                if(!isset($this->pg->isChanging[$sender->getName()])){
                    $sender->sendMessage("[Exemple] Your skin isnt changing for the moment");
                }else{
                    $sender->setSkin($this->pg->isChanging[$sender->getName()][0], $this->pg->isChanging[$sender->getName()][1]);
                    unset($this->pg->isChanging[$sender->getName()]);
                    $sender->sendMessage("[Exemple] Your skin is back to normal !");
                }
            return true;
            break;
        }
    }else{
        $sender->sendMessage("[Exemple] You need to provide an argument ! /skin <start/stop>");
    }
  }
}
