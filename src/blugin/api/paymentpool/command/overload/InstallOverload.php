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

namespace blugin\api\paymentpool\command\overload;

use blugin\api\paymentpool\task\InstallProviderTask;
use blugin\api\paymentpool\lib\command\BaseCommand;
use blugin\api\paymentpool\lib\command\handler\ICommandHandler;
use blugin\api\paymentpool\lib\command\overload\NamedOverload;
use blugin\api\paymentpool\lib\command\overload\Overload;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class InstallOverload extends NamedOverload implements ICommandHandler{
    public function __construct(BaseCommand $baseCommand){
        parent::__construct($baseCommand, "install");
        $this->setHandler($this);
    }

    /** @param mixed[] $args name => value */
    public function handle(CommandSender $sender, array $args, Overload $overload) : bool{
        Server::getInstance()->getAsyncPool()->submitTask(new InstallProviderTask());
        $overload->sendMessage($sender, "success");

        return true;
    }
}