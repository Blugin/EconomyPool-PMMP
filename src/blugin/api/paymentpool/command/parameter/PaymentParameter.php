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

namespace blugin\api\paymentpool\command\parameter;

use blugin\api\paymentpool\PaymentPool;
use blugin\api\paymentpool\lib\command\parameter\defaults\EnumParameter;
use blugin\api\paymentpool\lib\command\parameter\Parameter;
use pocketmine\command\CommandSender;

class PaymentParameter extends EnumParameter{
    public function getTypeName() : string{
        return "payment";
    }

    public function getFailureMessage(CommandSender $sender, string $argument) : ?string{
        return "commands.generic.invalidPayment";
    }

    public function prepare() : Parameter{
        $this->enum = PaymentPool::getInstance()->getProviderEnum();
        return $this;
    }

    public function valid(CommandSender $sender, string $argument) : bool{
        return true;
    }
}