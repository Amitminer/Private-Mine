<?php

declare(strict_types=1);

namespace PrivateMine;

use pocketmine\Server;
use PrivateMine\PVM;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\plugin\PluginBase;

class worldwhitelist{
     
    private Server $server;
    private $config;
    private $plugin;
    
    public function __construct(Config $config) {
        $this->config = $config;
    }
    
	public function executeWWL(Player $player){
	    
	    $server = Server::getInstance();
        $playerName = $player->getName();
        $Mine1 = $this->config->getNested("PrivateMinesSettings.Mine1.World");
        $pm1 = $this->config->getNested("PrivateMinesSettings.Mine1.permission");
        # executing console Command 
	    $server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()), "ranks setpermission {$playerName} {$pm1}");
	    $server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()), "ww add {$Mine1} {$playerName}");
	    $server->getWorldManager()->loadWorld($Mine1);
	    $world = $server->getWorldManager()->getWorldByName($Mine1);
	    $player->teleport($world->getSafeSpawn());
	}
}
