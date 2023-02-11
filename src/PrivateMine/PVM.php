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
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml");
        $this->saveDefaultConfig();
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
                    $this->ConfirmationMenu($player);
                break;
            }
        });
        
        # Mines List
        $form->setTitle("§l§cPrivate Mines");
        $form->addButton("DiamondBlock Mine", 0, "textures/blocks/diamond_block");
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
        $form->setContent("§l§9----- CONFIRM PURCHASE -----
§aItem: §bDiamond Block Mine
§cPrice: §e50Million 
Details: §cfor more Details. please visit discord server and check channel/pvm-preview..
§aBenefits: §2Unlimited access to diamondblock mine and increased mining capabilities

§aReference Number: §c#$ReferenceNumber

§cBy confirming this purchase, you agree to our terms of service and refund policy.

§aConfirm Purchase (Yes, I want to proceed with this purchase)
§cCancel (No, I want to go back to the previous menu)
");
        $form->setButton1("§l§aYes");
        $form->setButton2("§l§cNo");
        $player->sendForm($form);
        return $form;
    }
    # coal mine function
  public function coalmine(Player $player){
      $amount = 1000;
      $title = $this->config->get("title");
      libEco::reduceMoney($player, $amount, static function(bool $success) use($player, $title): void {
	if($success){
        $player->sendToastNotification($title , "§aYou have successfully bought DiamondBlock Mine! Thanks");
	} else{
		$player->sendMessage("§cIt looks like the money in your account aren't sufficient to make this purchase at this time.");
	}
});

  }
}
