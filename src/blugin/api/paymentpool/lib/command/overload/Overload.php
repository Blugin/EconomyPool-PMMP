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

namespace blugin\api\paymentpool\lib\command\overload;

use blugin\api\paymentpool\lib\command\BaseCommand;
use blugin\api\paymentpool\lib\command\handler\ClosureCommandHandler;
use blugin\api\paymentpool\lib\command\handler\ICommandHandler;
use blugin\api\paymentpool\lib\command\parameter\Parameter;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Overload{
    public const ERROR_NAME_MISMATCH = -1;
    public const ERROR_PARAMETER_INSUFFICIENT = -2;
    public const ERROR_PARAMETER_INVALID = -3;
    public const ERROR_PERMISSION_DENIED = -4;

    /** @var BaseCommand */
    protected $baseCommand;

    /** @var Parameter[] */
    protected $parameters = [];

    /** @var int */
    protected $requireLength = 0;

    /** @var ICommandHandler|null */
    protected $handler = null;

    public function __construct(BaseCommand $baseCommand){
        $this->baseCommand = $baseCommand;
    }

    public function getBaseCommand() : BaseCommand{
        return $this->baseCommand;
    }

    /** @param string[] $params */
    public function sendMessage(CommandSender $sender, string $str, array $params = []) : void{
        $this->getBaseCommand()->sendMessage($sender, $this->getMessageId($str), $params);
    }

    public function getMessageId(string $str) : string{
        return "commands.{$this->baseCommand->getLabel()}." . "$str";
    }

    public function getPermission() : string{
        return $this->baseCommand->getPermission();
    }

    public function testPermission(CommandSender $sender) : bool{
        if($this->getPermission() == "" | $sender->hasPermission($this->getPermission()))
            return true;

        $this->baseCommand->sendMessage($sender, TextFormat::RED . "%commands.generic.permission");
        return false;
    }

    /** @return Parameter[] */
    public function getParameters(Player $player) : array{
        return array_map(function(Parameter $parameter) use ($player): Parameter{
            $translatedParamater = clone $parameter;
            $translatedParamater->setName($parameter->getTranslatedName($this, $player));
            return $translatedParamater;
        }, $this->parameters);
    }

    public function addParamater(Parameter $parameter) : Overload{
        $index = count($this->parameters);
        $before = $this->parameters[$index - 1] ?? null;
        if($before instanceof Parameter){
            if($before->getLength() === PHP_INT_MAX)
                throw new \RuntimeException("You can't register parameter after infinite-length parameter");

            if($before->isOptional() && !$parameter->isOptional())
                throw new \RuntimeException("You can't register not-optional parameter after optional parameter");

            foreach($this->parameters as $oldParam){
                if($oldParam->getLabel() === $parameter->getLabel())
                    throw new \RuntimeException("You can't register multiple parameters with the same name");
            }
        }

        $parameter->setOverload($this);
        $this->parameters[] = $parameter;
        if(!$parameter->isOptional()){
            $this->requireLength += $parameter->getLength();
        }
        return $this;
    }

    public function getRequireLength() : int{
        return $this->requireLength;
    }

    public function getHandler() : ?ICommandHandler{
        return $this->handler;
    }

    /** @param ICommandHandler|\Closure|null $handler */
    public function setHandler($handler) : Overload{
        if($handler instanceof \Closure){
            $handler = new ClosureCommandHandler($handler);
        }
        $this->handler = $handler;
        return $this;
    }

    public function toUsageString(?CommandSender $sender = null) : string{
        return implode(" ", array_map(function(Parameter $parameter) use ($sender): string{
            return $parameter->toUsageString($this, $sender);
        }, $this->parameters));
    }

    /** @param string[] $args */
    public function valid(CommandSender $sender, array $args) : bool{
        $requireCount = $this->getRequireLength();
        $argsCount = count($args);

        if($argsCount < $requireCount)
            return false;

        $offset = 0;
        foreach($this->parameters as $parameter){
            if($offset > $argsCount)
                break;

            $argument = implode(" ", array_slice($args, $offset, $parameter->getLength()));
            if($parameter->valid($sender, $argument))
                return true;

            $offset += $parameter->getLength();
        }
        return true;
    }

    /**
     * @param string[] $args
     *
     * @return mixed[]|int name => value. if parse failed return int
     */
    public function parse(CommandSender $sender, array $args){
        if(!$this->testPermission($sender))
            return self::ERROR_PERMISSION_DENIED;

        $requireCount = $this->getRequireLength();
        $argsCount = count($args);
        if($argsCount < $requireCount)
            return self::ERROR_PARAMETER_INSUFFICIENT;

        $offset = 0;
        $results = [];
        foreach($this->parameters as $parameter){
            if($offset > $argsCount)
                break;

            if($parameter->isOptional() && empty($args[$offset])){
                $results[$parameter->getLabel()] = $parameter->getDefault();
                break;
            }

            $argument = implode(" ", array_slice($args, $offset, $parameter->getLength()));
            $result = $parameter->parse($sender, $argument);
            if($result === null)
                return self::ERROR_PARAMETER_INVALID;

            $offset += $parameter->getLength();
            $results[$parameter->getLabel()] = $result;
        }
        return $results;
    }

    /** @param mixed[] $args name => value */
    public function onParse(CommandSender $sender, array $args) : bool{
        return $this->handler === null ? false : $this->handler->handle($sender, $args, $this);
    }
}