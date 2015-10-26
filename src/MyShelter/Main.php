<?php

namespace MyShelter;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\plugin\PluginBase as Base;
use pocketmine\math\Vector3;
use pocketmine\block\Planks;
use pocketmine\block\Glass;
use pocketmine\block\Cobblestone;
use pocketmine\block\Wood;
use pocketmine\block\Furnace;
use pocketmine\block\Chest;
use pocketmine\block\Fire;
use pocketmine\block\StillWater;
use pocketmine\block\Glowstone;
use pocketmine\block\Air;
use pocketmine\block\Workbench;
use pocketmine\block\Stonecutter;
use pocketmine\block\StoneBricks;
use pocketmine\block\Slab;
use pocketmine\tile\Tile;
use pocketmine\block\GlassPane;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\InstantEnchantParticle;
use pocketmine\level\sound\BatSound;
use pocketmine\level\sound\LaunchSound;
use pocketmine\level\sound\GhastSound;
use pocketmine\level\sound\BlazeShootSound;


class Main extends Base implements Listener{
	public function onEnable(){
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
			if(!(is_dir($this->getDataFolder()."Shelters/"))){
			@mkdir($this->getDataFolder()."Shelters/");
		}
		 if(!(is_dir($this->getDataFolder()."Shelters+/"))){
		 @mkdir($this->getDataFolder()."Shelters+/");
		}
		 if(!(is_dir($this->getDataFolder()."UShelters/"))){
		 @mkdir($this->getDataFolder()."UShelters/");
		 }
		 if(!(is_dir($this->getDataFolder()."Towers/"))){
		 @mkdir($this->getDataFolder()."Towers/");
		 }
		$this->getLogger()->info("Everything loaded!");
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if(strtolower($command->getName()) == "ms"){
			if(!(isset($args[0]))){
				$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "Type /ms help");
				return true;
			}elseif(isset($args[0])){
				if($args[0] == "help"){
						if(!(isset($args[1])) or $args[1] == "1"){
						 $sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "List of commands for MyShelter");
							$sender->sendMessage(TextFormat::GREEN . "/ms free - free shelter");
							$sender->sendMessage(TextFormat::GREEN . "/ms special - special shelter");
							$sender->sendMessage(TextFormat::GREEN . "/ms underground - underground shelter");
							$sender->sendMessage(TextFormat::GREEN . "/ms tower - tower shelter");
							$sender->sendMessage(TextFormat::GREEN . "/ms info - informations about plugin");
							return true;
					}
				}elseif($args[0] == "free"){
					if($sender->hasPermission("ms") || $sender->hasPermission("ms.command") || $sender->hasPermission("ms.command.free")){
						$senderMs = $this->getDataFolder()."Shelters/".$sender->getName().".txt";
						if($sender->getLevel()->getName() == $this->getConfig()->get("Spawn")){
							$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You can't make an shelter in spawn");
							return true;							
						}else{
							if(!(file_exists($senderMs))){
								$this->makeNormal($sender->getName());
								$this->normalParticle($sender->getName());
								return true;
							}else{
								$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You already have an free shelter");
								return true;
							}
						}
					}else{
						$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You do not have permission to use this command");
						return true;
					}
				}elseif($args[0] == "special"){
					if($sender->hasPermission("ms") || $sender->hasPermission("ms.command") || $sender->hasPermission("ms.command.special")){
				 	$senderMs = $this->getDataFolder()."Shelters+/".$sender->getName().".txt";
						if($sender->getLevel()->getName() == $this->getConfig()->get("Spawn")){
							$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You can't make an shelter in spawn");
							return true;							
						}else{
							if(!(file_exists($senderMs))){
								$this->makeSpecial($sender->getName());
								$this->specialParticle($sender->getName());
								return true;
							}else{
								$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You already have an special shelter");
								return true;
							}
						}
					}else{
						$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You do not have permission to use this command");
						return true;
					}
				}elseif($args[0] == "underground"){
					if($sender->hasPermission("ms") || $sender->hasPermission("ms.command") || $sender->hasPermission("ms.command.underground")){
				 	$senderMs = $this->getDataFolder()."UShelters/".$sender->getName().".txt";
						if($sender->getLevel()->getName() == $this->getConfig()->get("Spawn")){
							$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You can't make an shelter in spawn");
							return true;							
						}else{
							if(!(file_exists($senderMs))){
								$this->makeUnderg($sender->getName());
								$this->undergParticle($sender->getName());
								return true;
							}else{
								$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You already have an underground shelter");
								return true;
							}
						}
					}else{
						$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You do not have permission to use this command");
						return true;
					}
				}elseif($args[0] == "tower"){
					if($sender->hasPermission("ms") || $sender->hasPermission("ms.command") || $sender->hasPermission("ms.command.tower")){
				 	$senderMs = $this->getDataFolder()."Towers/".$sender->getName().".txt";
						if($sender->getLevel()->getName() == $this->getConfig()->get("Spawn")){
							$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You can't make an tower in spawn");
							return true;							
						}else{
							if(!(file_exists($senderMs))){
								$this->makeTower($sender->getName());
								$this->towerParticle($sender->getName());
								return true;
							}else{
								$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You already have an tower");
								return true;
							}
						}
					}else{
						$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You do not have permission to use this command");
						return true;
					}
				}elseif($args[0] == "info"){
					if($sender->hasPermission("ms") || $sender->hasPermission("ms.command") || $sender->hasPermission("ms.command.info")){
				  $sender->sendMessage(TextFormat::YELLOW . "Plugin made by §cJelly9912");
				  $sender->sendMessage(TextFormat::YELLOW . "Version §cv1.0.1");
					}else{
						$sender->sendMessage(TextFormat::GOLD . "[MyShelter] " . TextFormat::RED . "You do not have permission to use this command");
						return true;
					}
				}
			}
		}
	}
	
	public function makeNormal($name){
		$player = $this->getServer()->getPlayer($name);

   $levelName = $this->getServer()->getPlayer($name)->getLevel();
   
 		$playerFile = fopen($this->getDataFolder()."Shelters/".$name.".txt", "w");
			fwrite($playerFile, $player->getLevel()->getName());
   
			// First floor
		
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y, $player->getPosition()->z-1), new Planks());

 // Second floor


$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+1, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+1, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+1, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+1, $player->getPosition()->z-1), new Planks());

 // Third floor

$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+2, $player->getPosition()->z-1), new Planks());

 // Fourth floor
 
 
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-1), new Planks());

 // Fourth floor
 
 
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-2), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-3), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-4), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-5), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-6), new Wood());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-7), new Wood());


 // Floor
 
 
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-7), new Cobblestone());

 // Decorations
 
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-2), new Furnace());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-3), new Furnace());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-4), new Furnace());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-5), new Workbench());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-6), new Stonecutter());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-7), new Chest());

 
   // Starter kit

      $player->getInventory()->addItem(Item::get(Item::WOODEN_DOOR, 0, 2));
      $player->getInventory()->addItem(Item::get(Item::STONE_AXE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::STONE_PICKAXE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::STONE_SWORD, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::STONE_HOE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::STONE_SHOVEL, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::TORCH, 0, 4));
      $player->getInventory()->addItem(Item::get(355, 0, 2));
      $player->getInventory()->addItem(Item::get(Item::STEAK, 0, 5));
      

			// Tip on spawn shelter
			
			$player->sendTip(TextFormat::GREEN . "Shelter was spawned!");
			
		}
		
		// Particle for free shelter
		
 public function normalParticle($name) {
        $player = $this->getServer()->getPlayer($name);
       	$sender = $this->getServer()->getPlayer($name);
        $level = $sender->getLevel();
        $x = $sender->getX();
        $y = $sender->getY();
        $z = $sender->getZ();
        $center = new Vector3($x, $y, $z);
        $particle = new AngryVillagerParticle($center);
        for($yaw = 0, $y = $center->y; $y < $center->y + 5; $yaw += (M_PI * 2) / 20, $y += 1 / 20) {
            $x = -sin($yaw) + $center->x;
            $z = cos($yaw) + $center->z;
            $particle->setComponents($x, $y, $z);
            $level->addParticle($particle);
            
       // Sound on spawn shelter
            
            	$player->getLevel ()->addSound ( new LaunchSound ( $player->getPosition () ), array($player) );
  }
 }
 
 
 
 // Special shelter
 
 public function makeSpecial($name){
		$player = $this->getServer()->getPlayer($name);

   $levelName = $this->getServer()->getPlayer($name)->getLevel();
   
 		$playerFile = fopen($this->getDataFolder()."Shelters+/".$name.".txt", "w");
			fwrite($playerFile, $player->getLevel()->getName());
			
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-2), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-3), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-4), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-5), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-6), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-7), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-7), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-6), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-5), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-4), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-3), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-2), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y, $player->getPosition()->z-1), new StoneBricks());

 // Second floor


$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+1, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+1, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-2), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-3), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-4), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-5), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-6), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-7), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-7), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-6), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-5), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-4), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-3), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-2), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+1, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+1, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+1, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+1, $player->getPosition()->z-1), new StoneBricks());

 // Third floor

$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-2), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-3), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-4), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-5), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-6), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-7), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-7), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-6), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-5), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-4), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-3), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-2), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+2, $player->getPosition()->z-1), new StoneBricks());

 // Fourth floor
 
 
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-2), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-3), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-4), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-5), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-6), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-7), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-6, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-8), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-7), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-6), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-5), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-4), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-3), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-2), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+7, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-1), new StoneBricks());

 // Fourth floor
 
 
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y+3, $player->getPosition()->z-7), new Slab());


 // Floor
 
 
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-5, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-7), new Wood());

$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+5, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

 // Decorations
 
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-2), new Furnace());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-3), new Furnace());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-4), new Furnace());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-5), new Workbench());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-6), new Stonecutter());
$levelName->setBlock(new Vector3($player->getPosition()->x+6, $player->getPosition()->y, $player->getPosition()->z-7), new Chest());

   // Fire
   
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y, $player->getPosition()->z-7), new Fire());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y, $player->getPosition()->z-7), new Fire());

   // Cobblestone
   
   
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+1, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+1, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+1, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+1, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+2, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+2, $player->getPosition()->z-7), new Cobblestone());





 
   // Starter kit


      $player->getInventory()->addItem(Item::get(Item::IRON_DOOR, 0, 2));
      $player->getInventory()->addItem(Item::get(Item::IRON_AXE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::IRON_PICKAXE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::IRON_SWORD, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::IRON_HOE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::IRON_SHOVEL, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::TORCH, 0, $this->getConfig()->get("Torches")));
      $player->getInventory()->addItem(Item::get(355, 0, 2));
      $player->getInventory()->addItem(Item::get(Item::STEAK, 0, $this->getConfig()->get("Steaks")));
      $player->getInventory()->addItem(Item::get(Item::DIAMOND, 0, $this->getConfig()->get("Diamonds")));
      $player->getInventory()->addItem(Item::get(Item::BOW, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::ARROW, 0, $this->getConfig()->get("Arrows")));

			// Tip on spawn shelter
			
			
			$player->sendTip(TextFormat::GREEN . "Special shelter was spawned!");
			
		}
 
   // Particle and sound for special shelter
 
 
 public function specialParticle($name) {
        $player = $this->getServer()->getPlayer($name);
        $sender = $this->getServer()->getPlayer($name);
        $level = $sender->getLevel();
        $x = $sender->getX();
        $y = $sender->getY();
        $z = $sender->getZ();
        $center = new Vector3($x, $y, $z);
        $particle = new HugeExplodeParticle($center);
        for($yaw = 0, $y = $center->y; $y < $center->y + 2; $yaw += (M_PI * 1.5) / 10, $y += 1 / 20) {
            $x = -sin($yaw) + $center->x;
            $z = cos($yaw) + $center->z;
            $particle->setComponents($x, $y, $z);
            $level->addParticle($particle);
            
            	$player->getLevel ()->addSound ( new BlazeShootSound ( $player->getPosition () ), array($player) );
    
  }
 }
 
 public function makeUnderg($name){
		$player = $this->getServer()->getPlayer($name);

   $levelName = $this->getServer()->getPlayer($name)->getLevel();
   
 		$playerFile = fopen($this->getDataFolder()."UShelters/".$name.".txt", "w");
			fwrite($playerFile, $player->getLevel()->getName());
			
			
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-2, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-3, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-4, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-5, $player->getPosition()->z-1), new Glass());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-6, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-7, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-8, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-9, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-10, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-11, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-12, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-13, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-14, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-15, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-16, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-17, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-18, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-19, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-20, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-21, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-22, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-23, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-24, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-25, $player->getPosition()->z-1), new StillWater());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-26, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-27, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-28, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-29, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-30, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-31, $player->getPosition()->z-1), new Glowstone());

   // Room
   
   
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-27, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-28, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-29, $player->getPosition()->z-2), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-27, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-28, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-29, $player->getPosition()->z-3), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-27, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-28, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-29, $player->getPosition()->z-4), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-27, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-28, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-29, $player->getPosition()->z-5), new Air());   
   
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-27, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-28, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-29, $player->getPosition()->z-2), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-27, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-28, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-29, $player->getPosition()->z-3), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-27, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-28, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-29, $player->getPosition()->z-4), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-27, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-28, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-29, $player->getPosition()->z-5), new Air());   
   
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-27, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-28, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-29, $player->getPosition()->z-2), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-27, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-28, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-29, $player->getPosition()->z-3), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-27, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-28, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-29, $player->getPosition()->z-4), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-27, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-28, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-29, $player->getPosition()->z-5), new Air());   
   
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-27, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-28, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-29, $player->getPosition()->z-2), new Furnace());   
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-27, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-28, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-29, $player->getPosition()->z-3), new Stonecutter());   
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-27, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-28, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-29, $player->getPosition()->z-4), new WorkBench());   
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-27, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-28, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-29, $player->getPosition()->z-5), new Chest());   
   
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-27, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-28, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-29, $player->getPosition()->z-2), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-27, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-28, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-29, $player->getPosition()->z-3), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-27, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-28, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-29, $player->getPosition()->z-4), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-27, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-28, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-29, $player->getPosition()->z-5), new Air());   
   
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-27, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-28, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-29, $player->getPosition()->z-2), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-27, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-28, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-29, $player->getPosition()->z-3), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-27, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-28, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-29, $player->getPosition()->z-4), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-27, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-28, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-29, $player->getPosition()->z-5), new Air());   
   
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-27, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-28, $player->getPosition()->z-2), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-29, $player->getPosition()->z-2), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-27, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-28, $player->getPosition()->z-3), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-29, $player->getPosition()->z-3), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-27, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-28, $player->getPosition()->z-4), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-29, $player->getPosition()->z-4), new Air());   
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-27, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-28, $player->getPosition()->z-5), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-29, $player->getPosition()->z-5), new Air());   





   // Starter kit


      $player->getInventory()->addItem(Item::get(Item::STONE_AXE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::STONE_PICKAXE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::STONE_SWORD, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::STONE_HOE, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::STONE_SHOVEL, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::TORCH, 0, 32));
      $player->getInventory()->addItem(Item::get(355, 0, 2));
      $player->getInventory()->addItem(Item::get(Item::STEAK, 0, 5));
      $player->getInventory()->addItem(Item::get(Item::GRASS, 0, 1));


   // Tip on spawn underground shelter
   
    
			$player->sendTip(TextFormat::GREEN . "Underground shelter was spawned!");
			
		}
 
 
   // Particle and sound for underground shelter
 
 
 public function undergParticle($name) {
        $player = $this->getServer()->getPlayer($name);
        $sender = $this->getServer()->getPlayer($name);
        $level = $sender->getLevel();
        $x = $sender->getX();
        $y = $sender->getY();
        $z = $sender->getZ();
        $center = new Vector3($x, $y, $z);
        $particle = new InstantEnchantParticle($center);
        for($yaw = 0, $y = $center->y; $y < $center->y + 10; $yaw += (M_PI * 1.5) / 10, $y += 1 / 20) {
            $x = -sin($yaw) + $center->x;
            $z = cos($yaw) + $center->z;
            $particle->setComponents($x, $y, $z);
            $level->addParticle($particle);
            
            	$player->getLevel ()->addSound ( new BatSound ( $player->getPosition () ), array($player) );
    
  }
 }
 
 
 	public function makeTower($name){
		$player = $this->getServer()->getPlayer($name);

   $levelName = $this->getServer()->getPlayer($name)->getLevel();
   
 		$playerFile = fopen($this->getDataFolder()."Towers/".$name.".txt", "w");
			fwrite($playerFile, $player->getLevel()->getName());
 
 
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+1, $player->getPosition()->z-1), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+1, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+1, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+1, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+1, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+1, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+1, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+1, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+1, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+1, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+1, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+1, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+1, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+1, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+1, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+2, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+2, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+2, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+2, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+2, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+2, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+2, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+2, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+2, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+2, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+2, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+2, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+2, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+2, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+2, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+2, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+3, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+3, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+3, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+3, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+3, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+3, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+3, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+3, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+3, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+4, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+4, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+4, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+4, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+4, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+4, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+4, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+4, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+4, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+4, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+4, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+4, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+4, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+4, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+4, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+4, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+4, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+4, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+4, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+4, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+4, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+4, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+4, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+4, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+5, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+5, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+5, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+5, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+5, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+5, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+5, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+5, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+5, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+5, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+5, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+5, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+5, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+5, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+6, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+6, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+6, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+6, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+6, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+6, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+6, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+6, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+6, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+6, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+6, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+6, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+6, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+6, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+6, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+6, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+6, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+6, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+6, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+6, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+6, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+6, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+6, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+6, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+7, $player->getPosition()->z-1), new GlassPane());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+7, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+7, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+7, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+7, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+7, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+7, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+7, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+7, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+7, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+7, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+7, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+7, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+7, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+7, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+7, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+7, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+7, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+7, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+7, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+7, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+7, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+7, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+7, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+8, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+8, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+8, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+8, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+8, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+8, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+8, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+8, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+8, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+8, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+8, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+8, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+8, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+8, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+8, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+8, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+8, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+8, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+8, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+8, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+8, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+8, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+8, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+8, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+9, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+9, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+9, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+9, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+9, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+9, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+9, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+9, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+9, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+9, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+9, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+9, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+9, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+9, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+9, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+9, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+9, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+9, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+9, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+9, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+9, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+9, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+9, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+9, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+10, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+10, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+10, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+10, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+10, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+10, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+10, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+10, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+10, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+10, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+10, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+10, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+10, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+10, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+11, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+11, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+11, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+11, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+11, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+11, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+11, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+11, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+11, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+11, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+11, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+11, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+11, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+11, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+11, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+11, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+11, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+11, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+11, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+11, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+11, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+11, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+11, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+11, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+12, $player->getPosition()->z-1), new GlassPane());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+12, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+12, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+12, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+12, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+12, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+12, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+12, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+12, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+12, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+12, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+12, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+12, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+12, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+12, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+12, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+12, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+12, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+12, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+12, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+12, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+12, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+12, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+12, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+13, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+13, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+13, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+13, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+13, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+13, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+13, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+13, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+13, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+13, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+13, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+13, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+13, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+13, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+13, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+13, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+13, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+13, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+13, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+13, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+13, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+13, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+13, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+13, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+14, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+14, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+14, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+14, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+14, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+14, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+14, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+14, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+14, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+14, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+14, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+14, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+14, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+14, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+14, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+14, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+14, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+14, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+14, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+14, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+14, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+14, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+14, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+14, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+15, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+15, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+15, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+15, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+15, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+15, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+15, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+15, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+15, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+15, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+15, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+15, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+15, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+15, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+16, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+16, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+16, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+16, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+16, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+16, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+16, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+16, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+16, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+16, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+16, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+16, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+16, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+16, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+16, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+16, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+16, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+16, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+16, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+16, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+16, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+16, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+16, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+16, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+17, $player->getPosition()->z-1), new GlassPane());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+17, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+17, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+17, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+17, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+17, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+17, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+17, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+17, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+17, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+17, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+17, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+17, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+17, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+17, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+17, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+17, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+17, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+17, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+17, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+17, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+17, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+17, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+17, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+18, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+18, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+18, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+18, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+18, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+18, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+18, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+18, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+18, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+18, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+18, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+18, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+18, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+18, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+18, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+18, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+18, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+18, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+18, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+18, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+18, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+18, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+18, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+18, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+19, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+19, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+19, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+19, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+19, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+19, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+19, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+19, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+19, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+19, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+19, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+19, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+19, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+19, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+19, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+19, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+19, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+19, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+19, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+19, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+19, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+19, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+19, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+19, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+20, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+20, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+20, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+20, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+20, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+20, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+20, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+20, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+20, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+20, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+20, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+20, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+20, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+20, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+21, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+21, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+21, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+21, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+21, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+21, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+21, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+21, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+21, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+21, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+21, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+21, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+21, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+21, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+21, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+21, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+21, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+21, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+21, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+21, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+21, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+21, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+21, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+21, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+22, $player->getPosition()->z-1), new GlassPane());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+22, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+22, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+22, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+22, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+22, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+22, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+22, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+22, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+22, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+22, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+22, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+22, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+22, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+22, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+22, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+22, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+22, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+22, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+22, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+22, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+22, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+22, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+22, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+23, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+23, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+23, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+23, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+23, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+23, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+23, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+23, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+23, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+23, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+23, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+23, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+23, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+23, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+23, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+23, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+23, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+23, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+23, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+23, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+23, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+23, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+23, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+23, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+24, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+24, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+24, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+24, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+24, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+24, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+24, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+24, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+24, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+24, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+24, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+24, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+24, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+24, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+24, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+24, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+24, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+24, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+24, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+24, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+24, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+24, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+24, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+24, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+25, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+25, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+25, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+25, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+25, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+25, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+25, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+25, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+25, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+25, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+25, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+25, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+25, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+25, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+26, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+26, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+26, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+26, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+26, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+26, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+26, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+26, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+26, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+26, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+26, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+26, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+26, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+26, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+26, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+26, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+26, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+26, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+26, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+26, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+26, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+26, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+26, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+26, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+27, $player->getPosition()->z-1), new GlassPane());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+27, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+27, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+27, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+27, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+27, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+27, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+27, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+27, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+27, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+27, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+27, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+27, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+27, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+27, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+27, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+27, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+27, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+27, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+27, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+27, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+27, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+27, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+27, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+28, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+28, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+28, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+28, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+28, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+28, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+28, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+28, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+28, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+28, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+28, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+28, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+28, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+28, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+28, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+28, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+28, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+28, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+28, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+28, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+28, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+28, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+28, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+28, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+29, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+29, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+29, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+29, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+29, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+29, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+29, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+29, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+29, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+29, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+29, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+29, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+29, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+29, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-9), new Cobblestone());

$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+30, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+30, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+30, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+30, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+30, $player->getPosition()->z-1), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+30, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+30, $player->getPosition()->z-2), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+30, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+30, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+30, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+30, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+4, $player->getPosition()->y+30, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+30, $player->getPosition()->z-3), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+30, $player->getPosition()->z-4), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+30, $player->getPosition()->z-5), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+30, $player->getPosition()->z-6), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-4, $player->getPosition()->y+30, $player->getPosition()->z-7), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+30, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+30, $player->getPosition()->z-8), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+30, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+30, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+30, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+30, $player->getPosition()->z-9), new Cobblestone());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+30, $player->getPosition()->z-9), new Cobblestone());


   // Floor
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-1), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-2), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-3), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-4), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-5), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-6), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y-1, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y-1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y-1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y-1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y-1, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y-1, $player->getPosition()->z-8), new Planks());

  // Floors
  
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-2), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-2), new Slab());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+29, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-3), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+29, $player->getPosition()->z-3), new Slab());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+29, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-4), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+29, $player->getPosition()->z-4), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+29, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-5), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+29, $player->getPosition()->z-5), new Slab());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+29, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-6), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+29, $player->getPosition()->z-6), new Slab());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+29, $player->getPosition()->z-7), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-7), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-7), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-7), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-7), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-7), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+29, $player->getPosition()->z-7), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+29, $player->getPosition()->z-8), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+29, $player->getPosition()->z-8), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+29, $player->getPosition()->z-8), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+29, $player->getPosition()->z-8), new Slab());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+29, $player->getPosition()->z-8), new Slab());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-2), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+25, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+25, $player->getPosition()->z-3), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+25, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+25, $player->getPosition()->z-4), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+25, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+25, $player->getPosition()->z-5), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+25, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+25, $player->getPosition()->z-6), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+25, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+25, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+25, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+25, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+25, $player->getPosition()->z-8), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+25, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+25, $player->getPosition()->z-8), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+26, $player->getPosition()->z-8), new Stonecutter());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+26, $player->getPosition()->z-8), new Furnace());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+26, $player->getPosition()->z-8), new WorkBench());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+26, $player->getPosition()->z-8), new Chest());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-2), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+20, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+20, $player->getPosition()->z-3), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+20, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+20, $player->getPosition()->z-4), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+20, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+20, $player->getPosition()->z-5), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+20, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+20, $player->getPosition()->z-6), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+20, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+20, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+20, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+20, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+20, $player->getPosition()->z-8), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+20, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+20, $player->getPosition()->z-8), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-2), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+15, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+15, $player->getPosition()->z-3), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+15, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+15, $player->getPosition()->z-4), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+15, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+15, $player->getPosition()->z-5), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+15, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+15, $player->getPosition()->z-6), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+15, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+15, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+15, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+15, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+15, $player->getPosition()->z-8), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+15, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+15, $player->getPosition()->z-8), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-2), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+10, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+10, $player->getPosition()->z-3), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+10, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+10, $player->getPosition()->z-4), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+10, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+10, $player->getPosition()->z-5), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+10, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+10, $player->getPosition()->z-6), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+10, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+10, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+10, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+10, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+10, $player->getPosition()->z-8), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+10, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+10, $player->getPosition()->z-8), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-2), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-2), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+5, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-3), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+5, $player->getPosition()->z-3), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+5, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-4), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+5, $player->getPosition()->z-4), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+5, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-5), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+5, $player->getPosition()->z-5), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+5, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-6), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+5, $player->getPosition()->z-6), new Planks());


$levelName->setBlock(new Vector3($player->getPosition()->x-3, $player->getPosition()->y+5, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-7), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+3, $player->getPosition()->y+5, $player->getPosition()->z-7), new Planks());

$levelName->setBlock(new Vector3($player->getPosition()->x-2, $player->getPosition()->y+5, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x-1, $player->getPosition()->y+5, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x, $player->getPosition()->y+5, $player->getPosition()->z-8), new Air());
$levelName->setBlock(new Vector3($player->getPosition()->x+1, $player->getPosition()->y+5, $player->getPosition()->z-8), new Planks());
$levelName->setBlock(new Vector3($player->getPosition()->x+2, $player->getPosition()->y+5, $player->getPosition()->z-8), new Planks());


 
   // Starter kit


      $player->getInventory()->addItem(Item::get(Item::IRON_SWORD, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::TORCH, 0, $this->getConfig()->get("TowerTorches")));
      $player->getInventory()->addItem(Item::get(Item::APPLE, 0, 5));
      $player->getInventory()->addItem(Item::get(Item::BREAD, 0, 2));
      $player->getInventory()->addItem(Item::get(Item::LADDER, 0, $this->getConfig()->get("Ladders")));    
      $player->getInventory()->addItem(Item::get(Item::WOODEN_DOOR, 0, 1));
      $player->getInventory()->addItem(Item::get(355, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::BOW, 0, 1));
      $player->getInventory()->addItem(Item::get(Item::ARROW, 0, $this->getConfig()->get("TowerArrows")));


   // Tip on spawn tower
   
    
			$player->sendTip(TextFormat::GREEN . "Tower was spawned!");
			
		}
 
 
   // Particle and sound on tower spawn
 
 
 public function towerParticle($name) {
        $player = $this->getServer()->getPlayer($name);
        $sender = $this->getServer()->getPlayer($name);
        $level = $sender->getLevel();
        $x = $sender->getX();
        $y = $sender->getY();
        $z = $sender->getZ();
        $center = new Vector3($x, $y, $z);
        $particle = new LavaParticle($center);
        for($yaw = 0, $y = $center->y; $y < $center->y + 40; $yaw += (M_PI * 2) / 20, $y += 1 / 20) {
            $x = -sin($yaw) + $center->x;
            $z = cos($yaw) + $center->z;
            $particle->setComponents($x, $y, $z);
            $level->addParticle($particle);
            
            	$player->getLevel ()->addSound ( new GhastSound ( $player->getPosition () ), array($player) );
    
  }
 } 
}

?>
