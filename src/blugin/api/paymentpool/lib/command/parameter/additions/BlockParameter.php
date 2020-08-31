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

namespace blugin\api\paymentpool\lib\command\parameter\additions;

use blugin\api\paymentpool\lib\command\parameter\defaults\StringParameter;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class BlockParameter extends StringParameter{
    public function getType() : int{
        return AvailableCommandsPacket::ARG_TYPE_INT;
    }

    public function getTypeName() : string{
        return "block";
    }

    public function getFailureMessage(CommandSender $sender, string $argument) : ?string{
        return "commands.give.block.notFound";
    }

    /** @return Block|null */
    public function parseSilent(CommandSender $sender, string $argument){
        try{
            $v = explode(":", $argument);
            if(!is_numeric($v[0]) || isset($v[1]) && !is_numeric($v[1]))
                return null;

            return BlockFactory::getInstance()->get((int) $v[0], (int) ($v[1] ?? 0));
        }catch(\InvalidArgumentException $e){
            return null;
        }
    }
}