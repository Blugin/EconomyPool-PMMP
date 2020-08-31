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

namespace blugin\lib\command\parameter\defaults;

use blugin\lib\command\enum\Enum;
use blugin\lib\command\enum\EnumFactory;
use blugin\lib\command\parameter\Parameter;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class PlayerParameter extends EnumParameter{
    /** @var bool Whether to include offline players */
    protected $includeOffline = false;

    public function getType() : int{
        return AvailableCommandsPacket::ARG_TYPE_TARGET;
    }

    public function getTypeName() : string{
        return "target";
    }

    public function getFailureMessage(CommandSender $sender, string $argument) : ?string{
        return "commands.generic.player.notFound";
    }

    public function prepare() : Parameter{
        $this->enum = EnumFactory::getInstance()->get($this->isIncludeOffline() ? Enum::PLAYERS_INCLUE_OFFLINE : Enum::PLAYERS);
        return $this;
    }

    public function valid(CommandSender $sender, string $argument) : bool{
        return preg_match("/^([a-zA-Z_][a-zA-Z_ 0-9]*)$/", $argument) !== false;
    }

    public function isIncludeOffline() : bool{
        return $this->includeOffline;
    }

    public function setIncludeOffline(bool $includeOffline) : PlayerParameter{
        $this->includeOffline = $includeOffline;
        return $this;
    }
}