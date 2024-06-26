<?php
namespace RimVG\wreg;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\{PlayerJoinEvent,PlayerQuitEvent,PlayerMoveEvent,PlayerJumpEvent,PlayerChatEvent,PlayerItemUseEvent,PlayerInteractEvent};
use pocketmine\event\block\{BlockBreakEvent,BlockPlaceEvent};
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\utils\Config;
use RimVG\wreg\{RimVGData,LoginRimVG,RimVGPass,TimeTask};

class LicenseRimVG extends PluginBase implements Listener{

	public $password, $login;
	
	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->password = new Config($this->getDataFolder()."password.yml",Config::YAML);
		$this->login = new Config($this->getDataFolder()."login.yml",Config::YAML);
		$this->getServer()->getCommandMap()->register("/register", new RimVGData($this));
		$this->getServer()->getCommandMap()->register("/login", new LoginRimVG($this));
		$this->getServer()->getCommandMap()->register("/loginmc", new RimVGPass($this));
		$this->getLogger()->notice("EasyRegisterLG plugin has been successfully activated!");
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$player=$ev->getPlayer();
		$name=$player->getName();
		$min=$this->getConfig()->get("time-login");
		$sec=(int)((int)($min)*60);
		$this->getScheduler()->scheduleRepeatingTask(new TimeTask($this, $player), 20*(int)($sec));
		if(!$this->login->exists($name)){
			$this->login->set($name, false);
			$this->login->save();
		}
		$player->sendMessage("§l§6• Please Login To Continue The Game!");
	}
	
	public function onQuit(PlayerQuitEvent $ev){
		$name=$ev->getPlayer()->getName();
		$this->login->set($name, false);
		$this->login->save();
	}
	
	public function onMove(PlayerMoveEvent $ev){
		$player=$ev->getPlayer();
		$name=$player->getName();
		if(!$this->password->exists($name)){
			$player->sendPopup("§l§6• Please Register Account, Use: /register");
			$ev->cancel();
			return;
		}
		if($this->login->get($name)!==true){
			$player->sendPopup("§l§6• Please Login Account, Use: /login");
			$ev->cancel();
		}
	}
	
	public function onJump(PlayerJumpEvent $ev){
		$player=$ev->getPlayer();
		$name=$player->getName();
		if(!$this->password->exists($name)){
			$player->sendPopup("§l§6• Please Register Account, Use: /register");
			return;
		}
		if($this->login->get($name)!==true){
			$player->sendPopup("§l§6• Please Login Account, Use: /login");
		}
	}
	
	public function onChat(PlayerChatEvent $ev){
		$player=$ev->getPlayer();
		$name=$player->getName();
		if(!$this->password->exists($name)){
			$player->sendPopup("§l§6• Please Register Account, Use: /register");
			$ev->cancel();
			return;
		}
		if($this->login->get($name)!==true){
			$player->sendPopup("§l§6• Please Login Account, Use: /login");
			$ev->cancel();
		}
	}
	
	public function onInteract(PlayerInteractEvent $ev){
		$player=$ev->getPlayer();
		$name=$player->getName();
		if(!$this->password->exists($name)){
			$player->sendPopup("§l§6• Please Register Account, Use: /register");
			$ev->cancel();
			return;
		}
		if($this->login->get($name)!==true){
			$player->sendPopup("§l§6• Please Login Account, Use: /login");
			$ev->cancel();
		}
	}
	
	public function onUse(PlayerItemUseEvent $ev){
		$player=$ev->getPlayer();
		$name=$player->getName();
		if(!$this->password->exists($name)){
			$player->sendPopup("§l§6• Please Register Account, Use: /register");
			$ev->cancel();
			return;
		}
		if($this->login->get($name)!==true){
			$player->sendPopup("§l§6• Please Login Account, Use: /login");
			$ev->cancel();
		}
	}
	
	public function onBreak(BlockBreakEvent $ev){
		$player=$ev->getPlayer();
		$name=$player->getName();
		if(!$this->password->exists($name)){
			$player->sendPopup("§l§6• Please Register Account, Use: /register");
			$ev->cancel();
			return;
		}
		if($this->login->get($name)!==true){
			$player->sendPopup("§l§6• Please Login Account, Use: /login");
			$ev->cancel();
		}
	}
	
	public function onPlace(BlockPlaceEvent $ev){
		$player=$ev->getPlayer();
		$name=$player->getName();
		if(!$this->password->exists($name)){
			$player->sendPopup("§l§6• Please Register Account, Use: /register");
			$ev->cancel();
			return;
		}
		if($this->login->get($name)!==true){
			$player->sendPopup("§l§6• Please Login Account, Use: /login");
			$ev->cancel();
		}
	}
	
	public function onItemPickup(EntityItemPickupEvent $ev){
		$player=$ev->getEntity();
		if(!$player instanceof Player){
			return;
		}
		if($ev->isCancelled()){
			return;
		}
		$name=$player->getName();
		if(!$this->password->exists($name)){
			$player->sendPopup("§l§6• Please Register Account, Use: /register");
			$ev->cancel();
			return;
		}
		if($this->login->get($name)!==true){
			$player->sendPopup("§l§6• Please Login Account, Use: /login");
			$ev->cancel();
		}
	}
}
