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

namespace blugin\api\paymentpool\lib\command\config;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class CommandConfig extends Config{
    /** @var PluginBase */
    protected $owningPlugin;

    /** @var CommandConfigData[] name => command config data */
    protected $dataMap;

    public function __construct(PluginBase $owningPlugin){
        parent::__construct("{$owningPlugin->getDataFolder()}command.yml", self::YAML);
        $this->owningPlugin = $owningPlugin;
        $this->dataMap = CommandConfigData::parse($this->getAll());
    }

    /** @return CommandConfigData[] */
    public function getDataMap() : array{
        return $this->dataMap;
    }

    public function getData(string $name) : ?CommandConfigData{
        return $this->dataMap[$name] ?? null;
    }

    public function getOwningPlugin() : PluginBase{
        return $this->owningPlugin;
    }
}