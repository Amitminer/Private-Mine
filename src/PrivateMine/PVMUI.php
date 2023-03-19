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
        if($this->config->getNested("PrivateMinesSettings.Mine1.enable") === true){
            $form->setTitle("§l§cPrivate Mines");
            $form->addButton($this->config->getNested("PrivateMinesSettings.Mine1.name"), 0, $this->config->getNested("PrivateMinesSettings.Mine1.ui-texture"));
            $player->sendForm($form);
            return $form;
        }
   }
    
   public function ConfirmationMenu($player) {
       $form = new ModalForm(function (Player $player, bool $data) {
            switch($data) {
                case true:
                    $this->Mine1($player);
                    break;
                case false:
                    $this->tpmine1($player);
                    break;
            }
        });
        $ReferenceNumber = mt_rand(0,9999);
        $form->setTitle($this->config->getNested("PrivateMinesSettings.Mine1.name"));
        $form->setContent($this->config->get("confirm-msg"));
        $form->setButton1("§l§aYes");
        $form->setButton2("§l§cteleport");
        $player->sendForm($form);
        return $form;
    }
   public function tpmine1(Player $player){
       if ($player->hasPermission($this->config->getNested("PrivateMinesSettings.Mine1.permission")) {
           $player->sendMessage("§a§l[OmniCraft] §cteleporting to privatemine.");
           $server = Server::getInstance();
           $playerName = $player->getName();
           $Mine1 = $this->config->getNested("PrivateMinesSettings.Mine1.World");
	       $server->getWorldManager()->loadWorld($Mine1);
	       $world = $server->getWorldManager()->getWorldByName($Mine1);
	       $player->teleport($world->getSafeSpawn());
            
        } else{
            $player->sendMessage("§cIt looks like you haven't purchased a Private Mine yet. for purchasing pvm do/pvm");
   }
   }
    #mine1 function
   public function Mine1(Player $player){
       $config = $this->config;
       $server = Server::getInstance();
       $plugin = $server->getPluginManager()->getPlugin("PrivateMine");
       libEco::reduceMoney($player, $config->getNested("PrivateMinesSettings.Mine1.Price"), static function(bool $success) use($player, $config,$plugin): void {
	if($success){
        $successMsg = str_replace('{name}', $config->getNested("PrivateMinesSettings.Mine1.name"), $config->get('success-msg'));
        $player->sendToastNotification($config->get("title"), $successMsg);
        $wwl = new worldwhitelist($config);
        $wwl->executeWWL($player);
        #$wwl();
	} else{
		$player->sendMessage($config->get("money-insufficient"));
	}
});
        return true;
  }
}
