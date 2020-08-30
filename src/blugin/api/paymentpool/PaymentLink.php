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
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  Blugin team
 * @link    https://github.com/Blugin
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace blugin\api\paymentpool;

use pocketmine\plugin\Plugin;
use pocketmine\Server;

class PaymentLink implements \JsonSerializable{
    /** @var string */
    private $name;

    /** @var string|null */
    private $default;

    public function __construct(string $name, ?string $default = null){
        $this->name = $name;
        $this->default = $default;
    }

    public function getName() : string{
        return $this->name;
    }

    public function getDefault() : ?string{
        return $this->default;
    }

    public function setDefault(?string $default) : void{
        $this->default = $default;
    }

    public function getPlugin() : ?Plugin{
        return Server::getInstance()->getPluginManager()->getPlugin($this->name);
    }

    public function jsonSerialize() : ?string{
        return $this->default;
    }
}
