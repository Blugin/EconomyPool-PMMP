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

use pocketmine\command\CommandSender;

final class LocaleConverter{
    private function __construct(){
    }

    /**
     * @var string[] IETF_language_tag => ISO_639-3 code
     *
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://en.wikipedia.org/wiki/ISO_639-3
     *
     * The list was taken from language_names.json in the Minecraft resource.
     * @link https://github.com/ZtechNetwork/MCBVanillaResourcePack/blob/cd647b3/texts/language_names.json
     *
     * I created this mapping data by referring to the dataset on the site below.
     * @link http://github.com/datasets/language-codes/blob/d8f5a13/data/language-codes-3b2.csv
     */
    public const LANGUAGES_MAP = [
        "en_US" => "eng", //English (US)
        "en_GB" => "eng", //English (UK)
        "de_DE" => "ger", //Deutsch (Deutschland)
        "es_ES" => "spa", //Español (España)
        "es_MX" => "spa", //Español (México)
        "fr_FR" => "fre", //Français (France)
        "fr_CA" => "fre", //Français (Canada)
        "it_IT" => "ita", //Italiano (Italia)
        "ja_JP" => "jpn", //日本語 (日本)
        "ko_KR" => "kor", //한국어 (대한민국)
        "pt_BR" => "por", //Português (Brasil)
        "pt_PT" => "por", //Português (Portugal)
        "ru_RU" => "rus", //Русский (Россия)
        "zh_CN" => "chs", //简体中文
        "zh_TW" => "chs", //繁體中文
        "nl_NL" => "nld", //Nederlands (Nederland)
        "bg_BG" => "bul", //Български (BG)
        "cs_CZ" => "cze", //Čeština (Česká republika)
        "da_DK" => "dan", //Dansk (DA)
        "el_GR" => "gre", //Ελληνικά (Ελλάδα)
        "fi_FI" => "fin", //Suomi (Suomi)
        "hu_HU" => "hun", //Magyar (HU)
        "id_ID" => "ind", //Bahasa Indonesia (Indonesia)
        "nb_NO" => "nor", //Norsk bokmål (Norge)
        "pl_PL" => "pol", //Polski (PL)
        "sk_SK" => "slo", //Slovensky (SK)
        "sv_SE" => "swe", //Svenska (Sverige)
        "tr_TR" => "tur", //Türkçe (Türkiye)
        "uk_UA" => "ukr", //Українська (Україна)
    ];

    /**
     * @return string the locale name that matches the command sender's language setting, or fallback
     */
    public static function fromSender(CommandSender $sender, string $fallback = "eng") : string{
        return method_exists($sender, 'getLocale') ? self::fromIETF($sender->getLocale(), $fallback) : $fallback;
    }

    /**
     * @return string the locale name converted from IETF_language_tag to ISO_639-3 code
     */
    public static function fromIETF(string $ietfTag, string $fallback = "eng") : string{
        return self::LANGUAGES_MAP[$ietfTag] ?? $fallback;
    }
}