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

namespace ayd1ndemirci\PlayTime\command;

use ayd1ndemirci\PlayTime\event\PlayerListener;
use ayd1ndemirci\PlayTime\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class PlayTimeCommand extends Command
{
    public function __construct()
    {
        parent::__construct(Main::$config->get("Command")["Command"], Main::$config->get("Command")["Description"]);
        $this->setAliases(Main::$config->get("Command")["Aliases"]);
        $this->setUsage(Main::$config->get("Command")["Usage"]);
    }

    /**
     * @throws \Exception
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if ($sender instanceof Player) {
            if (empty($args[0])) {
                foreach (Main::$main->getProvider()->getPlayer($sender->getName()) as $datum) {
                    $set_time = time() - PlayerListener::$join_time[$sender->getName()];
                    $time = $set_time + $datum["playTime"];
                    $time = $time - (floor($time / 31556926) * 31556926);
                    $day = floor(($time / 3600) / 24);
                    $time = $time - (($day * 24) * 3600);
                    $hour = floor($time / 3600);
                    $time = $time - ($hour * 3600);
                    $minute = floor($time / 60);
                    $second = ($time - ($minute * 60));
                    $sender->sendMessage(str_replace(["{day}", "{hour}", "{minutes}", "{second}"], [$day, $hour, $minute, $second], Main::$config->get("Messages")["Play-Time"]));
                }
            }else {
                foreach (Main::$main->getProvider()->getPlayer($args[0]) as $datum) {
                    $time = $datum["playTime"];
                    $time = $time - (floor($time / 31556926) * 31556926);
                    $day = floor(($time / 3600) / 24);
                    $time = $time - (($day * 24) * 3600);
                    $hour = floor($time / 3600);
                    $time = $time - ($hour * 3600);
                    $minute = floor($time / 60);
                    $second = ($time - ($minute * 60));
                    $sender->sendMessage(str_replace(["{player}","{day}", "{hour}", "{minutes}", "{second}"], [$args[0], $day, $hour, $minute, $second], Main::$config->get("Messages")["Players-Play-Time"]));
                }
            }
        }
    }
}
