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

namespace blugin\lib\command\translator;

class Language{
    /** @var string locale name */
    protected $locale;

    /** @var string[] id => text */
    protected $map = [];

    public function __construct(array $map, string $locale){
        $this->map = $map;
        $this->locale = $locale;
    }

    public function get(string $id) : string{
        return $this->map[$id] ?? $id;
    }

    public function getName() : string{
        return $this->get("language.name");
    }

    public function getLocale() : string{
        return $this->locale;
    }

    /**
     * @return Language|null the loaded language from file
     */
    public static function loadFrom(string $path, string $locale) : ?Language{
        if(!file_exists($path))
            return null;

        return new Language(array_map("stripcslashes", parse_ini_file($path, false, INI_SCANNER_RAW)), strtolower($locale));
    }
}