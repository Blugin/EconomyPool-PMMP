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

use pocketmine\permission\Permission;

class CommandConfigData{
    /** @var string */
    protected $label;

    /** @var string */
    protected $name;

    /** @var string */
    protected $permission = "op";

    /** @var string[] */
    protected $aliases = [];

    /** @var CommandConfigData[] */
    protected $childrens = [];

    public function __construct(string $label, array $configData){
        $this->label = $label;

        if(!isset($configData["name"]))
            throw new CommandConfigException("Command configuration must have \"name\" string");

        if(!is_string($configData["name"]))
            throw new CommandConfigException("\"name\" data is must be string, " . gettype($configData["name"]) . " given");

        $this->name = (string) $configData["name"];

        if(isset($configData["permission"])){
            try{
                $this->permission = (string) Permission::getByName($configData["permission"]);
            }catch(\InvalidArgumentException $e){
                throw new CommandConfigException("Invalid permission name : \"{$configData["permission"]}}\" ");
            }
        }

        if(isset($configData["aliases"])){
            if(!is_array($configData["aliases"]))
                throw new CommandConfigException("\"aliases\" is must be array, " . gettype($configData["aliases"]) . " given");

            foreach($configData["aliases"] as $alias){
                if(!is_string($alias))
                    throw new CommandConfigException("\"aliases\" data is must be string, " . gettype($alias) . " given");

                $this->aliases[] = (string) $alias;
            }
        }

        if(isset($configData["children"])){
            if(!is_array($configData["children"]))
                throw new CommandConfigException("\"children\" is must be array, " . gettype($configData["children"]) . " given");

            $this->childrens = self::parse($configData["children"]);
        }
    }

    public function getLabel() : string{
        return $this->label;
    }

    public function getName() : string{
        return $this->name;
    }

    public function getPermission() : string{
        return $this->permission;
    }

    /** @return string[] */
    public function getAliases() : array{
        return $this->aliases;
    }

    /** @return CommandConfigData[] */
    public function getChildrens() : array{
        return $this->childrens;
    }

    public function getChildren(string $name) : ?CommandConfigData{
        return $this->childrens[$name] ?? null;
    }

    /**
     * @params mixed[][]
     *
     * @return CommandConfigData[]
     */
    public static function parse(array $dataMap) : array{
        $result = [];
        foreach($dataMap as $label => $dataConfig){
            if(!is_array($dataConfig))
                throw new CommandConfigException("data is must be array, " . gettype($dataConfig) . " given");

            $data = new CommandConfigData((string) $label, $dataConfig);
            $result[$data->getLabel()] = $data;
        }
        return $result;
    }
}