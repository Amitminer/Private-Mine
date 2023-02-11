<?php

declare(strict_types=1);

namespace PrivateMine;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\ToastRequestPacket;
# LibEco by Davidglitch04
use davidglitch04\libEco\libEco;
# FormsUI (lib) by Vecnavium
use Vecnavium\FormsUI;
use Vecnavium\FormsUI\Form;
use Vecnavium\FormsUI\SimpleForm;
use Vecnavium\FormsUI\ModalForm;

class Main extends PluginBase implements Listener {
    # onLoading plugin
    public function onEnable() : void{
        $this->getLogger()->info("Private mine enabled!!");
    }
    
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        switch ($command->getName()) {
            case "pvm":
                if($sender instanceof Player) {
                           $this->pvmUI($sender);
                     } else {
                             $sender->sendMessage("Please execute this command in-game");
                              return true;
                     }
            break;
        }
        return true;
    }
    # PVM UI function 
  public function pvmUI(Player $player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->coalmine($player);
                break;
            }
        });
        
        # Mines List
        $form->setTitle("§l§cPrivate Mines");
        $form->addButton("Coal Mine", 0, "textures/items/coal");
        $player->sendForm($form);
        return $form;
    }
    # coal mine function
  public function coalmine(Player $player){
      $amount = 1000;
      libEco::reduceMoney($player, $amount, static function(bool $success) use($player): void {
	if($success){
	    $player->sendMessage("§aYou have successfully bought Coalmine! Thanks");
	} else{
		$player->sendMessage("§cIt looks like the money in your account aren't sufficient to make this purchase at this time.");
	}
}); # closing function 

  }
}
