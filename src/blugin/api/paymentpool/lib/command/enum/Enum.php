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

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\Server;

class Enum extends CommandEnum{
    public const BOOLEAN = "bool";
    public const PLAYERS = "target";
    public const PLAYERS_INCLUE_OFFLINE = "allplayer";
    public const WORLDS = "worlds";

    /** @var string */
    protected $name;

    /** @var mixed[] name => value */
    protected $values;

    /** @param mixed[]|null $values name => value */
    public function __construct(string $name, ?array $values = null){
        $this->name = $name;
        $this->values = $values ?? [];
    }

    public function getName() : string{
        return $this->name;
    }

    /** @return string[] name[] */
    public function getValues() : array{
        return array_map(function(string $value) : string{
            if(strpos($value, " ") !== false)
                $value = "\"$value\"";
            return $value;
        }, array_keys($this->values));
    }

    /** @return mixed[] name => value */
    public function getAll(){
        return $this->values;
    }

    /** @param mixed[] $values name => value */
    public function setAll(array $values) : Enum{
        $this->values = [];
        foreach($values as $name => $value){
            $this->values[(string) $name] = $value;
        }
        $this->onUpdate();
        return $this;
    }

    public function has(string $name) : bool{
        return isset($this->values[$name]);
    }

    /** @return mixed|null */
    public function get(string $name){
        return $this->values[$name] ?? null;
    }

    public function set(string $name, $value) : Enum{
        if(!isset($this->values[$name]) || $this->values[$name] !== $value){
            $this->values[$name] = $value;
            $this->onUpdate();
        }
        return $this;
    }

    public function remove(string $name) : Enum{
        if(isset($this->values[$name])){
            unset($this->values[$name]);
            $this->onUpdate();
        }
        return $this;
    }

    protected function onUpdate() : void{
        /*
         * TODO: Figure out how to use softEnums
         *
         * $pk = new UpdateSoftEnumPacket();
         * $pk->enumName = $this->getName();
         * $pk->values = $this->getValues();
         * $pk->type = UpdateSoftEnumPacket::TYPE_SET;
         * Server::getInstance()->broadcastPackets(Server::getInstance()->getOnlinePlayers(), [$pk]);
        */
        foreach(Server::getInstance()->getOnlinePlayers() as $player){
            $player->getNetworkSession()->syncAvailableCommands();
        }
    }
}