<?php

declare(strict_types=1);

namespace PrivateMine;

use PrivateMine\worldwhitelist;
use PrivateMine\PVMUI;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;

class PVM extends PluginBase implements Listener {
    
    public $config;
    private static $instance;
    
    # onEnabling plugin
    public function onEnable() : void{
        self::$instance = $this;
        $this->getLogger()->info("Private mine enabled!!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml");
        $this->saveDefaultConfig();
    }
    # executing cmd
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        $pvmui = new PVMUI($this->config);
        switch ($command->getName()) {
            case "pvm":
                if($sender instanceof Player) {
                           $pvmui->pvmUI($sender);
                     } else {
                             $sender->sendMessage("Please execute this command in-game");
                              return true;
                     }
            break;
        }
        return true;
    }
}
