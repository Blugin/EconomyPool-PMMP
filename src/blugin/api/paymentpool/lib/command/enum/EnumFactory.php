<?php

/*
 *
 *  ____  _             _         _____
 * | __ )| |_   _  __ _(_)_ __   |_   _|__  __ _ _ __ ___
 * |  _ \| | | | |/ _` | | '_ \    | |/ _ \/ _` | '_ ` _ \
 * | |_) | | |_| | (_| | | | | |   | |  __/ (_| | | | | | |
 * |____/|_|\__,_|\__, |_|_| |_|   |_|\___|\__,_|_| |_| |_|
 *                |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License.
 *
 * @author  Blugin team
 * @link    https://github.com/Blugin
 * @license https://www.gnu.org/licenses/mit MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace blugin\api\paymentpool\lib\command\enum;

use pocketmine\Player;
use pocketmine\Server;

class EnumFactory{
    /** @var EnumFactory|null */
    protected static $instance = null;

    public static function getInstance() : EnumFactory{
        if(self::$instance === null){
            self::$instance = new EnumFactory();
        }
        return self::$instance;
    }

    /** @var Enum[] name => enum */
    protected $enums = [];

    private function __construct(){
        $this->set(Enum::BOOLEAN, ["true" => true, "false" => false]);

        $playersEnum = $this->set(Enum::PLAYERS);
        $players = [];
        foreach(Server::getInstance()->getOnlinePlayers() as $player){
            $players[strtolower($player->getName())] = $player;
        }
        $playersEnum->setAll($players);

        $playersEnum = $this->set(Enum::PLAYERS_INCLUE_OFFLINE);
        $players = array_map(function(Player $player) : string{
            return $player->getName();
        }, $players);
        foreach(scandir(Server::getInstance()->getDataPath() . "players/") as $fileName){
            if(substr($fileName, -4) === ".dat"){
                $playerName = substr($fileName, 0, -4);
                if(!isset($players[strtolower($playerName)])){
                    $players[strtolower($playerName)] = $playerName;
                }
            }
        }
        $playersEnum->setAll($players);

        $worldsEnum = $this->set(Enum::WORLDS);
        $worlds = [];
        foreach(Server::getInstance()->getLevels() as $world){
            $worldName = strtolower($world->getFolderName());
            $worlds[$worldName] = $world;
        }
        $worldsEnum->setAll($worlds);
    }

    /** @return Enum[] */
    public function getAll() : array{
        return $this->enums;
    }

    /** @param mixed[]|null $values name => value */
    public function get(string $name) : ?Enum{
        return $this->enums[$name] ?? null;
    }

    /** @param mixed[]|null $values name => value */
    public function set(string $name, array $values = []) : Enum{
        if(!isset($this->enums[$name])){
            $this->enums[$name] = new Enum($name, $values);
        }elseif($values !== null && $this->enums[$name]->getAll() !== $values){
            $this->enums[$name]->setAll($values);
        }
        return $this->enums[$name];
    }
}