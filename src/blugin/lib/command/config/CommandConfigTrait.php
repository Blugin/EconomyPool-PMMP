<?php /** @noinspection PhpParamsInspection */

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

namespace blugin\lib\command\config;

use pocketmine\plugin\PluginBase;

/**
 * This trait override most methods in the {@link PluginBase} abstract class.
 */
trait CommandConfigTrait{
    /** @var CommandConfig */
    private $commandConfig = null;

    public function getCommandConfig() : CommandConfig{
        if($this->commandConfig === null){
            $this->loadCommandConfig();
        }
        return $this->commandConfig;
    }

    public function loadCommandConfig() : void{
        if(!$this->saveDefaultCommandConfig())
            throw new CommandConfigException("Default command configuration file not found");

        $this->commandConfig = new CommandConfig($this);
    }

    public function saveDefaultCommandConfig() : bool{
        $configFile = "{$this->getDataFolder()}command.yml";
        if(file_exists($configFile))
            return true;

        $resource = $this->getResource("command/{$this->getServer()->getLanguage()->getLang()}.yml");
        if($resource === null){
            foreach($this->getResources() as $filePath => $info){
                if(preg_match('/^command\/[a-zA-Z]{3}\.yml$/', $filePath)){
                    $resource = $this->getResource($filePath);
                    break;
                }
            }
        }
        if($resource === null)
            return false;

        $ret = stream_copy_to_stream($resource, $fp = fopen($configFile, "wb")) > 0;
        fclose($fp);
        fclose($resource);
        return $ret;
    }
}