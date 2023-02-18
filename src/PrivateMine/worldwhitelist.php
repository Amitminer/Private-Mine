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
    
	public static function executeWWL(Player $player){
	    
	    $server = Server::getInstance();
        $worldName = "test";
        $playerName = $player->getName();
        # executing console Command 
	    $server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()), "ww add {$worldName} {$playerName}");
	    $server->getWorldManager()->loadWorld($worldName);
	    $world = $server->getWorldManager()->getWorldByName($worldName);
	    $player->teleport($world->getSafeSpawn());
	}
}