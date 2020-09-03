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

namespace blugin\api\paymentpool\lib\command\listener;

use blugin\api\paymentpool\lib\command\BaseCommand;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\Server;

class AvaliableCommandListener implements Listener{
    /**
     * @param DataPacketSendEvent $event
     *
     * @priority HIGHEST
     */
    public function onDataPacketSend(DataPacketSendEvent $event) : void{
        $packet = $event->getPacket();
        if($packet instanceof AvailableCommandsPacket){
            foreach($packet->commandData as $name => $commandData){
                $command = Server::getInstance()->getCommandMap()->getCommand($name);
                if($command instanceof BaseCommand){
                    $commandData->overloads = $command->asOverloadsArray($event->getPlayer());
                }
            }
        }
    }
}
