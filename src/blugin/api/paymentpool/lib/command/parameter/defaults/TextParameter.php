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
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class TextParameter extends Parameter{
    public function getType() : int{
        return AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
    }

    public function getTypeName() : string{
        return "text";
    }

    public function prepare() : Parameter{
        $this->setLength(PHP_INT_MAX);
        return $this;
    }
}