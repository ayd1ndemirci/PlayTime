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

namespace ayd1ndemirci\PlayTime\provider;

use SQLite3;
use ayd1ndemirci\PlayTime\Main;

class SQLite
{
    public SQLite3 $playtime;

    public function __construct()
    {
        $this->playtime = new SQLite3(Main::$main->getDataFolder() . "playtime.db");
        $this->playtime->exec("CREATE TABLE IF NOT EXISTS playtime(playerName String, playTime INT)");
    }
    public function addPlayer(string $playerName, int $time):void
    {
        $data = $this->playtime->prepare("INSERT INTO playtime(playerName, playTime) VALUES (:playerName, :playTime)");
        $data->bindParam(":playerName", $playerName);
        $data->bindParam(":playTime", $time);
        $data->execute();
    }
    public function getPlayer(string $playerName):array
    {
        $result = [];
        $data = $this->playtime->prepare("SELECT * FROM playtime WHERE playerName = :playerName");
        $data->bindParam(":playerName", $playerName);
        $control = $data->execute();
        while ($row = $control->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }
    public function addTime(string $playerName, int $time):void
    {
        $data = $this->playtime->prepare("UPDATE playtime SET playTime = :playTime WHERE playerName = :playerName");
        $data->bindParam(":playerName", $playerName);
        $data->bindParam(":playTime", $time);
        $data->execute();
    }
}
