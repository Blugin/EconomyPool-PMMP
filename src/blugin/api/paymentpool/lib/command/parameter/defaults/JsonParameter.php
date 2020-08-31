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

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class JsonParameter extends TextParameter{
    public function getType() : int{
        return AvailableCommandsPacket::ARG_TYPE_JSON;
    }

    public function getTypeName() : string{
        return "json";
    }

    public function valid(CommandSender $sender, string $argument) : bool{
        return preg_match("/^{([.]*)}$/", $argument);
    }

    /** @return mixed[]|null the parsed json array */
    public function parseSilent(CommandSender $sender, string $argument){
        $data = json_decode($argument, true);
        return $data === null || !is_array($data) ? null : $data;
    }
}