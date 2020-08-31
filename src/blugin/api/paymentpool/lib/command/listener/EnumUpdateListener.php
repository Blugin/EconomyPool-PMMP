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

use blugin\api\paymentpool\lib\command\enum\Enum;
use blugin\api\paymentpool\lib\command\enum\EnumFactory;
use pocketmine\event\level\LevelLoadEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EnumUpdateListener implements Listener{
    /**
     * @priority MONITOR
     */
    public function onPlayerJoin(PlayerJoinEvent $event) : void{
        $player = $event->getPlayer();
        EnumFactory::getInstance()->get(Enum::PLAYERS)->set(strtolower($player->getName()), $player);
    }

    /**
     * @priority MONITOR
     */
    public function onPlayerQuit(PlayerQuitEvent $event) : void{
        $player = $event->getPlayer();
        EnumFactory::getInstance()->get(Enum::PLAYERS)->remove(strtolower($player->getName()));
    }

    /**
     * @priority MONITOR
     */
    public function onWorldLoad(LevelLoadEvent $event) : void{
        $world = $event->getLevel();
        EnumFactory::getInstance()->get(Enum::WORLDS)->set(strtolower($world->getFolderName()), $world);
    }

    /**
     * @priority MONITOR
     */
    public function onWorldUnload(LevelLoadEvent $event) : void{
        $world = $event->getLevel();
        EnumFactory::getInstance()->get(Enum::WORLDS)->remove(strtolower($world->getFolderName()));
    }
}
