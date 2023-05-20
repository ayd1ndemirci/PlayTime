<?php

/*
                 _ __           _                _          _
                | /_ |         | |              (_)        (_)
  __ _ _   _  __| || |_ __   __| | ___ _ __ ___  _ _ __ ___ _
 / _` | | | |/ _` || | '_ \ / _` |/ _ \ '_ ` _ \| | '__/ __| |
| (_| | |_| | (_| || | | | | (_| |  __/ | | | | | | | | (__| |
 \__,_|\__, |\__,_||_|_| |_|\__,_|\___|_| |_| |_|_|_|  \___|_|
        __/ |
       |___/
 */

namespace ayd1ndemirci\PlayTime;

use ayd1ndemirci\PlayTime\command\PlayTimeCommand;
use ayd1ndemirci\PlayTime\event\PlayerListener;
use ayd1ndemirci\PlayTime\provider\SQLite;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    public static Main $main;
    public SQLite $sqlite;
    public static Config $config;
    public function onLoad():void
    {
        self::$main = $this;
        $this->saveResource("config.yml");
        self::$config = new Config(self::$main->getDataFolder()."config.yml", 2);
    }
    protected function onEnable(): void
    {
        $this->getLogger()->info("PlayTime avtice - @ayd1ndemirci");
        $this->sqlite = new SQLite();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
        $this->getServer()->getCommandMap()->register("playtime", new PlayTimeCommand());
    }
    public function getProvider():SQLite
    {
        return $this->sqlite;
    }
}
