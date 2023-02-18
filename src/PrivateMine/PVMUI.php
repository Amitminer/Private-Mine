<?php

declare(strict_types=1);

namespace PrivateMine;

use pocketmine\Server;
use PrivateMine\PVM;
use PrivateMine\worldwhitelist;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\ToastRequestPacket;
use pocketmine\plugin\PluginBase;
# FormsUI (lib) by Vecnavium
use Vecnavium\FormsUI;
use Vecnavium\FormsUI\Form;
use Vecnavium\FormsUI\SimpleForm;
use Vecnavium\FormsUI\ModalForm;
# LibEco by Davidglitch04
use davidglitch04\libEco\libEco;

class PVMUI {
    
    private $config;
    
    public function __construct(Config $config) {
        $this->config = $config;
    }
    # PVM UI function 
   public function pvmUI(Player $player){
       $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->ConfirmationMenu($player);
                break;
            }
        });
        
        # Mines List
        $form->setTitle("§l§cPrivate Mines");
        $form->addButton("DiamondBlock Mine", 0, "textures/items/diamond");
        $player->sendForm($form);
        return $form;
    }
    
   public function ConfirmationMenu($player) {
       $form = new ModalForm(function (Player $player, bool $data) {
            switch($data) {
                case true:
                    $this->coalmine($player);
                    break;
                case false:
                    $this->pvmUI($player);
                    break;
            }
        });
        $ReferenceNumber = mt_rand(0,9999);
        $form->setTitle("§3Diamond Block Mine");
        $form->setContent($this->config->get("confirm-msg"));
        $form->setButton1("§l§aYes");
        $form->setButton2("§l§cNo");
        $player->sendForm($form);
        return $form;
    }
    # coal mine function
   public function coalmine(Player $player){
       $config = $this->config;
       libEco::reduceMoney($player, $config->get("Amount"), static function(bool $success) use($player, $config): void {
	if($success){
        $player->sendToastNotification($config->get("title") , $config->get("success-msg"));
        worldwhitelist::executeWWL($player);
        #$wwl();
	} else{
		$player->sendMessage($config->get("money-insufficient"));
	}
});
        return true;
  }
}