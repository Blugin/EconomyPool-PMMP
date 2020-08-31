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

namespace blugin\lib\command\parameter\additions;

use blugin\lib\command\parameter\defaults\StringParameter;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class ItemParameter extends StringParameter{
    public function getType() : int{
        return AvailableCommandsPacket::ARG_TYPE_INT;
    }

    public function getTypeName() : string{
        return "item";
    }

    public function getFailureMessage(CommandSender $sender, string $argument) : ?string{
        return "commands.give.item.notFound";
    }

    /** @return Item|null */
    public function parseSilent(CommandSender $sender, string $argument){
        try{
            return ItemFactory::fromStringSingle($argument);
        }catch(\InvalidArgumentException $e){
            return null;
        }
    }
}