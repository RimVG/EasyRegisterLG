<?php

declare(strict_types=1);

namespace RimVG\wreg;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\{Command, CommandSender};
use pocketmine\console\ConsoleCommandSender;
use RimVG\wreg\LicenseRimVG;

class RimVGData extends Command implements PluginOwned {
	
	private LicenseRimVG $plugin;
	
	 public function __construct(LicenseRimVG $plugin){
		$this->plugin = $plugin;
		parent::__construct("register", "Register", null, ["rgt"]);
		$this->setPermission("register.cmd");
	}
	
	public function execute(CommandSender $player, string $label, array $args){
		if($player instanceof Player){
			if(!isset($args[0])){
				$player->sendMessage("§l§c•§a Use:§e /register <password>");
				return true;
			}
			$name=$player->getName();
			if($this->plugin->password->exists($name)){
				$player->sendMessage("§l§c• Your Account Has Been Existed In System");
				return true;
			}
			$this->plugin->password->set($name, $args[0]);
			$this->plugin->password->save();
			$this->plugin->login->set($name, true);
			$this->plugin->login->save();
			$player->sendMessage("§l§c•§a Registered Successfully");
			$player->sendMessage("§l§c•§a Logined Successfully");
		}else{
			$player->sendMessage("There's nothing here, you can use it in the game!");
		}
	}
	
	public function getOwningPlugin(): LicenseRimVG{
		return $this->plugin;
	}
}
