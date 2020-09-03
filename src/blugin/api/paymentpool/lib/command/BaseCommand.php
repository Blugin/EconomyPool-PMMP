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

namespace blugin\api\paymentpool\lib\command;

use blugin\api\paymentpool\lib\command\config\CommandConfigData;
use blugin\api\paymentpool\lib\command\overload\NamedOverload;
use blugin\api\paymentpool\lib\command\overload\Overload;
use blugin\api\paymentpool\lib\command\parameter\Parameter;
use blugin\api\paymentpool\lib\translator\TranslatorHolder;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\lang\TranslationContainer;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class BaseCommand extends Command implements PluginIdentifiableCommand{
    /** @var PluginBase */
    private $owningPlugin;

    /** @var Overload[] */
    protected $overloads = [];

    /** @var CommandConfigData */
    protected $configData;

    /** @param string[] $aliases */
    public function __construct(string $label, PluginBase $owner, CommandConfigData $configData){
        if(!$owner instanceof TranslatorHolder)
            throw new \InvalidArgumentException("BaseCommand's plugin must implement TranslatorHolder.");

        parent::__construct($configData->getName(), "", null, $configData->getAliases());
        $this->owningPlugin = $owner;
        $this->configData = $configData;

        $this->setLabel($label);
        $permissionName = "{$this->getLabel()}.cmd";
        $this->setPermission($permissionName);
        $this->recalculatePermission($permissionName, $configData->getPermission());
        $this->setDescription($this->getMessage(null, "commands.{$this->getLabel()}.description"));
    }

    /** @param string[] $args */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if(!$this->owningPlugin->isEnabled() || !$this->testPermission($sender))
            return false;

        if(empty($this->overloads))
            return false;

        foreach($this->overloads as $key => $overload){
            if($overload->valid($sender, $args)){
                $result = $overload->parse($sender, $args);
                switch($result){
                    case Overload::ERROR_NAME_MISMATCH:
                        break;
                    case Overload::ERROR_PARAMETER_INVALID:
                    case Overload::ERROR_PARAMETER_INSUFFICIENT:
                        $this->sendMessage($sender, "commands.generic.usage", ["/$commandLabel " . $overload->toUsageString($sender)]);
                        return true;
                    case Overload::ERROR_PERMISSION_DENIED:
                        $this->sendMessage($sender, TextFormat::RED . "%commands.generic.permission");
                        return true;
                    default:
                        return is_numeric($result) ? true : $overload->onParse($sender, $result);
                }
            }
        }
        $this->sendMessage($sender, "commands.generic.usage", [$this->getUsage($sender, $commandLabel)]);
        return true;
    }

    public function getUsage(?CommandSender $sender = null, ?string $commandLabel = null) : string{
        $usage = "/" . $commandLabel ?? $this->getName() . "}";

        $count = count($this->overloads);
        if($count === 0)
            return $usage;

        if($count === 1)
            return "$usage {$this->overloads[0]->toUsageString($sender)}";

        return "$usage <" . implode(" | ", array_map(function(Overload $overload) use ($sender): string{
                return $overload instanceof NamedOverload ? $overload->getTranslatedName($sender) : $overload->toUsageString($sender);
            }, $this->overloads)) . ">";
    }

    public function getMessage(?CommandSender $sender, string $str, array $params = []) : string{
        $str = $this->owningPlugin->getTranslator()->translateTo($str, $params, $sender);
        return Server::getInstance()->getLanguage()->translateString($str, $params);
    }

    /** @param string[] $params */
    public function sendMessage(CommandSender $sender, string $str, array $params = []) : void{
        $sender->sendMessage(new TranslationContainer($this->getMessage($sender, $str, $params), $params));
    }

    /** @return Overload[] */
    public function getOverloads() : array{
        return $this->overloads;
    }

    public function addOverload(?Overload $overload = null) : BaseCommand{
        if($overload === null){
            $overload = new Overload($this);
        }
        $this->overloads[] = $overload;
        if($overload instanceof NamedOverload){
            $childData = $this->getConfigData()->getChildren($overload->getLabel());
            $overload->setName($childData->getName());
            $overload->setAliases($childData->getAliases());
            $this->recalculatePermission($overload->getPermission(), $childData->getPermission());
        }
        return $this;
    }

    public function addNamedOverload(string $name) : BaseCommand{
        return $this->addOverload(new NamedOverload($this, $name));
    }

    /**
     * @return Parameter[][]
     */
    public function asOverloadsArray(Player $player) : array{
        $overloads = [];
        foreach($this->overloads as $overload){
            $overloads[] = $overload->getParameters($player);
        }
        return $overloads;
    }

    public function recalculatePermission(string $permissionName, string $default) : void{
        $permissionManager = PermissionManager::getInstance();
        $permission = $permissionManager->getPermission($permissionName);
        if($permission === null){
            $permission = new Permission($permissionName);
            $permissionManager->addPermission($permission);
        }
        $permission->setDefault($default);
    }

    public function getConfigData() : CommandConfigData{
        return $this->configData;
    }

    /** @return PluginBase */
    public function getPlugin() : Plugin{
        return $this->owningPlugin;
    }
}