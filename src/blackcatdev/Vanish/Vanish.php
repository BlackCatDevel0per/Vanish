<?php
 #  _   _       _   _           _____             
 # | \ | |     | | (_)         |  __ \            
 # |  \| | __ _| |_ ___   _____| |  | | _____   __
 # | . ` |/ _` | __| \ \ / / _ \ |  | |/ _ \ \ / /
 # | |\  | (_| | |_| |\ V /  __/ |__| |  __/\ V / 
 # |_| \_|\__,_|\__|_| \_/ \___|_____/ \___| \_/  
 #
 # Больше плагинов в https://vk.com/native_dev
 # По вопросам native.dev@mail.ru

declare(strict_types=1);

namespace blackcatdev\Vanish;

use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Vanish extends PluginBase{


	protected $vanish = [];

	public function onEnable() : void{
		$this->getLogger()->info("§2Плагин §3[Vanish] §2Запущен! §1https://vk.com/native_dev");
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
	}

	public function onDisable() : void{
	    $this->getLogger()->info("§cПлагин §3[Vanish] §cВыключен §1https://vk.com/native_dev");
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		if($cmd->getName() === "vanish"){
			
			if(!$sender instanceof Player){

				$sender->sendMessage($this->getConfig()->get("prefix") . TextFormat::RED . $this->getConfig()->get("noconsole"));
				return false;
			}

			if(!$sender->hasPermission("vanish.cmd")){
				$sender->sendMessage($this->getConfig()->get("prefix") . TextFormat::RED . $this->getConfig()->get("noperms"));
				return false;
			}

			if(empty($args[0])){
				
				if(!isset($this->vanish[$sender->getName()])){
					$this->vanish[$sender->getName()] = true;
					$sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
					$sender->setNameTagVisible(false);
					$sender->sendMessage($this->getConfig()->get("vanished"));

				}elseif(isset($this->vanish[$sender->getName()])){

					unset($this->vanish[$sender->getName()]);
					$sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
					$sender->setNameTagVisible(true);
					$sender->sendMessage($this->getConfig()->get("unvanished"));
				}
				return false;
			}
			if(!$sender->hasPermission("vanish.other")){
				$sender->sendMessage($this->getConfig()->get("prefix") . $this->getConfig()->get("noperms"));
				return false;
			}
			if($this->getServer()->getPlayer($args[0])){
				$player = $this->getServer()->getPlayer($args[0]);

				if(!isset($this->vanish[$player->getName()])){
					$this->vanish[$player->getName()] = true;
					$player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
					$player->setNameTagVisible(false);
					$player->sendMessage($this->getConfig()->get("vanished"));

				}elseif(isset($this->vanish[$player->getName()])){

					unset($this->vanish[$player->getName()]);
					$player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
					$player->setNameTagVisible(true);
					$player->sendMessage($this->getConfig()->get("unvanished"));;
				}

			}else{

				$sender->sendMessage($this->getConfig()->get("prefix") . TextFormat::RED . "Игрок не найден");
				return false;
			}
		}
		return true;
	}
}