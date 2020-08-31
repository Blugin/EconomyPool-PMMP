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

namespace blugin\api\paymentpool\lib\translator\traits;

use blugin\api\paymentpool\lib\translator\Translator;
use pocketmine\plugin\PluginBase;

/**
 * This trait override most methods in the {@link PluginBase} abstract class.
 */
trait TranslatorHolderTrait{
    /** @var Translator */
    private $translator;

    public function getTranslator() : Translator{
        return $this->translator;
    }

    /**
     * Load language with save default language resources
     */
    public function loadLanguage(?string $locale = null) : void{
        $this->saveDefaultLocales();

        /** @noinspection PhpParamsInspection */
        $this->translator = new Translator($this);
        if($locale !== null){
            $this->translator->setDefaultLocale($locale);
        }
    }

    /**
     * Save default language resources
     */
    public function saveDefaultLocales(){
        foreach($this->getResources() as $filePath => $info){
            if(preg_match('/^locale\/[a-zA-Z]{3}\.ini$/', $filePath)){
                $this->saveResource($filePath);
            }
        }
    }
}