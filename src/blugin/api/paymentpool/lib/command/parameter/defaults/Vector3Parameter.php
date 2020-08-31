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

namespace blugin\api\paymentpool\lib\command\parameter\defaults;

use blugin\api\paymentpool\lib\command\parameter\Parameter;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;

class Vector3Parameter extends Parameter{
    /** @var bool Whether to rounding down the coordinates */
    protected $isFloor = false;

    public function getType() : int{
        return AvailableCommandsPacket::ARG_TYPE_POSITION;
    }

    public function getTypeName() : string{
        return "x y z";
    }

    public function prepare() : Parameter{
        $this->setLength(3);
        return $this;
    }

    public function valid(CommandSender $sender, string $argument) : bool{
        $pattern = "/^" . ($sender instanceof Player ? "(~|~\+)?" : "") . "-?(\d+|\d*\.\d+)$/";
        foreach(explode(" ", $argument) as $coord){
            if(!preg_match($pattern, $coord))
                return false;
        }
        return true;
    }

    /** @return Vector3|null */
    public function parseSilent(CommandSender $sender, string $argument){
        if(!$this->valid($sender, $argument))
            return null;

        $argument = explode(" ", $argument);
        $coords = [
            "x" => $argument[0],
            "y" => $argument[1],
            "z" => $argument[2]
        ];
        foreach($coords as $coordName => &$coord){
            if($sender instanceof Player && strpos($coord, "~") === 0){
                /** @var Player $sender */
                $coord = $sender->getLocation()->{$coordName} + (float) substr($coord, 1);
            }else{
                $coord = (float) $coord;
            }

            if($this->isFloor()){
                $coord = (int) $coord;
            }
        }
        return new Vector3(...$coords);
    }

    public function isFloor() : bool{
        return $this->isFloor;
    }

    public function setFloor(bool $isFloor) : Vector3Parameter{
        $this->isFloor = $isFloor;
        return $this;
    }
}