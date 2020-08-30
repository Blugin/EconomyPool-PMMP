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

use blugin\api\paymentpool\command\overload\InstallOverload;
use blugin\api\paymentpool\command\overload\LinksOverload;
use blugin\api\paymentpool\command\overload\PaymentsOverload;
use blugin\api\paymentpool\command\overload\SetOverload;
use blugin\lib\command\BaseCommandTrait;
use blugin\lib\command\enum\Enum;
use blugin\lib\command\enum\EnumFactory;
use blugin\lib\command\translator\traits\TranslatorHolderTrait;
use blugin\lib\command\translator\TranslatorHolder;
use pocketmine\plugin\PluginBase;

class PaymentPool extends PluginBase implements TranslatorHolder{
    use TranslatorHolderTrait, BaseCommandTrait;

    private static $instance;

    public static function getInstance() : PaymentPool{
        return self::$instance;
    }

    public static function on($option = null, bool $default = true) : ?IPaymentProvider{
        return self::getInstance()->getProvider($option, $default);
    }

    public const DEFAULT_NAME = "@";
    public const ENUM_PROVIDERS = "Payment";
    public const ENUM_PLUGININFOS = "PaymentLink";

    /** @var Enum name => IPaymentProvider */
    private $providerEnum;

    /** @var Enum name => PaymentLink */
    private $linkEnum;

    /** @var IPaymentProvider[] save name => economy provider */
    private $providerSaveNames = [];

    public function onLoad(){
        self::$instance = $this;

        $this->providerEnum = EnumFactory::getInstance()->set(self::ENUM_PROVIDERS);
        $this->linkEnum = EnumFactory::getInstance()->set(self::ENUM_PLUGININFOS);
        $this->registerLink(self::DEFAULT_NAME);

        $this->loadLanguage();
        $this->getBaseCommand("payment");

        //Load provider scripts
        $this->getServer()->getPluginManager()->loadPlugins($this->getPrividerFolder());
        $this->getServer()->getPluginManager()->loadPlugins($this->getDataFolder() . "tests/");
    }

    public function onEnable() : void{
        //Register main command with subcommands
        $command = $this->getBaseCommand("payment");
        $command->addOverload(new SetOverload($command));
        $command->addOverload(new PaymentsOverload($command));
        $command->addOverload(new LinksOverload($command));
        $command->addOverload(new InstallOverload($command));
        $this->getServer()->getCommandMap()->register($this->getName(), $command);

        //Load plugin info data
        $filePath = "{$this->getDataFolder()}links.json";
        if(!file_exists($filePath))
            return;

        $content = file_get_contents($filePath);
        if($content === false)
            throw new \RuntimeException("Unable to find links.json file");

        $linkData = json_decode($content, true);
        if(!is_array($linkData))
            throw new \RuntimeException("Unable to decode links.json file");

        foreach($linkData as $name => $default){
            try{
                $this->linkEnum->set($name, new PaymentLink($name, empty($default) ? null : (string) $default));
            }catch(\Exception $e){
                $this->linkEnum->setAll([]);
                throw new \RuntimeException("[data.json] Unable to parse info data");
            }
        }
    }

    public function onDisable() : void{
        //Unregister main command with subcommands
        $this->getServer()->getCommandMap()->unregister($this->getBaseCommand("payment"));

        //Save plugin info data
        $filePath = "{$this->getDataFolder()}links.json";
        file_put_contents($filePath, json_encode($this->getLinks(), JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING));
        $this->linkEnum->setAll([]);
    }

    public function getPrividerFolder() : string{
        $path = $this->getDataFolder() . "providers/";
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }
        return $path;
    }

    public function getProviderEnum() : Enum{
        return $this->providerEnum;
    }

    /** @return IPaymentProvider[] name => provider */
    public function getProviders() : array{
        return $this->providerEnum->getAll();
    }

    /** @param string|object|null $value string or null or object (has getName()). If it was null, return default provider */
    public function getProvider($name = null, bool $default = true) : ?IPaymentProvider{
        $providerName = null;
        $name = $this->getNameFrom($name, "getProvider");
        if(is_string($name)){
            if($this->linkEnum->has($name)){
                $providerName = $this->linkEnum->get($name)->getDefault();
            }else{
                $providerName = $name;
            }
        }

        $providerName = strtolower($providerName ?? "");
        $provider = $this->providerEnum->get($providerName) ?? $this->providerSaveNames[$providerName] ?? null;
        if($provider === null && $default){
            $provider = $this->providerEnum->get(strtolower($this->getDefault())) ?? null;
        }

        return $provider;
    }

    /** @param string[] $saveNames */
    public function registerProvider(IPaymentProvider $provider, array $saveNames = []) : void{
        if($this->getDefault() === null){
            $this->setDefault($provider->getName());
        }

        $this->providerEnum->set(strtolower($provider->getName()), $provider);
        foreach($saveNames as $name){
            $this->providerSaveNames[strtolower($name)] = $provider;
        }
    }

    public function getLinkEnum() : Enum{
        return $this->linkEnum;
    }

    /** @return PaymentLink[] */
    public function getLinks() : array{
        return $this->linkEnum->getAll();
    }

    /** @param string|object $value string or null or object (has getName()) */
    public function getLink($name) : ?PaymentLink{
        return $this->linkEnum->get($this->getNameFrom($name, "getLink")) ?? null;
    }

    /** @param string|object $value string or null or object (has getName()) */
    public function registerLink($name) : void{
        $name = $this->getNameFrom($name, "getLink");
        if($name === null)
            throw new \RuntimeException("Argument 1 passed to Payment::getLink() must be of the type string or object with 'getName' method, " . gettype($name) . " given");

        $this->linkEnum->set($name, new PaymentLink($name, $this->getDefault()));
    }

    public function getDefault() : ?string{
        $defaultLink = $this->getLink(self::DEFAULT_NAME);
        $default = $defaultLink === null ? null : $defaultLink->getDefault();
        $providers = $this->getProviders();
        return $default ?? (empty($providers) ? null : array_key_first($providers));
    }

    public function setDefault(?string $default) : void{
        $this->getLink(self::DEFAULT_NAME)->setDefault($default);
    }

    /** @param string|object|null $value string or null or object (has getName()) */
    private function getNameFrom($value, string $methodName) : ?string{
        if($value === null)
            return null;

        if(is_object($value) && method_exists($value, "getName")){
            $value = $value->getName();
        }

        if(!is_string($value))
            throw new \RuntimeException("Argument 1 passed to Payment::$methodName() must be of the type string or null or object with 'getName' method, " . gettype($value) . " given");

        return $value;
    }
}
