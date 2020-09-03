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

namespace blugin\api\paymentpool\lib\command\parameter;

use blugin\api\paymentpool\lib\command\BaseCommand;
use blugin\api\paymentpool\lib\command\enum\Enum;
use blugin\api\paymentpool\lib\command\overload\Overload;
use blugin\api\paymentpool\lib\command\traits\LabelHolderTrait;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

abstract class Parameter extends CommandParameter{
    use LabelHolderTrait;

    /** @var Overload|null */
    protected $overload = null;

    /** @var int length of parameter */
    protected $length = 1;

    /** @var Enum|null */
    public $enum;

    /** @var mixed */
    protected $default = null;

    public function __construct(string $name = null, bool $optional = false, ?Enum $enum = null, ?Overload $overload = null){
        $this->overload = $overload;

        $this->paramName = $name;
        $this->isOptional = $optional;
        $this->enum = $enum;
        $this->setLabel($name);
        $this->prepare();
        $this->paramType = $this->getParamType();
        $this->flags = $this->getFlags();
    }

    public function getOverload() : ?Overload{
        return $this->overload;
    }

    public function setOverload(Overload $overload) : Parameter{
        $this->overload = $overload;
        return $this;
    }

    public function getBaseCommand() : ?BaseCommand{
        return $this->overload !== null ? $this->overload->getBaseCommand() : null;
    }

    /** @param string[] $params */
    public function sendMessage(CommandSender $sender, string $str, array $params = []) : void{
        $this->getBaseCommand()->sendMessage($sender, $str, $params);
    }

    public function getName() : ?string{
        return $this->paramName;
    }

    public function setName(?string $name) : Parameter{
        $this->paramName = $name;
        return $this;
    }

    public function getParamType() : int{
        return $this->getType() | AvailableCommandsPacket::ARG_FLAG_VALID;
    }

    public function isOptional() : bool{
        return $this->isOptional;
    }

    public function setOptional(bool $isOptional) : Parameter{
        $this->isOptional = $isOptional;
        return $this;
    }

    public function getFlags() : int{
        return $this->flags;
    }

    public function getEnum() : ?Enum{
        return $this->enum;
    }

    public function setEnum(?Enum $enum) : Parameter{
        $this->enum = $enum;
        return $this;
    }

    public function getDefault(){
        return $this->default;
    }

    public function setDefault($default) : Parameter{
        $this->default = $default;
        return $this;
    }

    public function getPostfix() : ?string{
        return $this->postfix;
    }

    public function setPostfix(?string $postfix) : Parameter{
        $this->postfix = $postfix;
        return $this;
    }

    public function getLength() : int{
        return $this->length;
    }

    public function setLength(int $length) : Parameter{
        $this->length = $length;
        return $this;
    }

    public function getFailureMessage(CommandSender $sender, string $argument) : ?string{
        return "commands.generic.parameter.invalid";
    }

    public function toUsageString(Overload $overload, ?CommandSender $sender = null) : string{
        $name = $this->getTranslatedName($overload, $sender) . ": " . $this->getTypeName();
        return $this->isOptional() ? "[$name]" : "<$name>";
    }

    public function getTranslatedName(Overload $overload, ?CommandSender $sender = null) : string{
        $messageId = $overload->getMessageId("parameter.{$this->getLabel()}");
        $name = $this->getBaseCommand()->getMessage($sender, $messageId);
        return $messageId === $name ? $this->getName() : $name;
    }

    public function prepare() : Parameter{
        return $this;
    }

    public function valid(CommandSender $sender, string $argument) : bool{
        return true;
    }

    /** @return string */
    public function parse(CommandSender $sender, string $argument){
        $result = $this->parseSilent($sender, $argument);
        if($result !== null)
            return $result;

        $failureMessage = $this->getFailureMessage($sender, $argument);
        if(is_string($failureMessage)){
            $this->getBaseCommand()->sendMessage($sender, $failureMessage, explode(" ", $argument));
        }
        return null;
    }

    /** @return string */
    public function parseSilent(CommandSender $sender, string $argument){
        return $argument;
    }

    abstract public function getType() : int;

    abstract public function getTypeName() : string;
}