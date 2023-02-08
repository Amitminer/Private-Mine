<?php

declare(strict_types=1);

namespace PrivateMine;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
# FormsUI (lib) by Vecnavium
use Vecnavium\FormsUI;
use Vecnavium\FormsUI\Form;
use Vecnavium\FormsUI\CustomForm;
use Vecnavium\FormsUI\SimpleForm;
use pocketmine\plugin\PluginBase;

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
    # UI
  public function pvmUI(Player $player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->getServer()->dispatchCommand($player, "say coalMine");
                break;
            }
        });
        # Mines List
        $form->setTitle("§l§cPrivate Mines");
        $form->addButton("Coal Mine", 0, "textures/items/coal");
        $player->sendForm($form);
        return $form;
    }
}
