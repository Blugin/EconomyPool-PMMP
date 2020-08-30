<?php /** @noinspection PhpUndefinedMethodInspection */

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

namespace blugin\api\paymentpool\traits;

use blugin\api\paymentpool\IPaymentProvider;
use blugin\api\paymentpool\PaymentPool;
use pocketmine\Player;

trait PaymentTrait{
    public static function getPaymentProvider() : ?IPaymentProvider{
        if(!method_exists(self::class, 'getInstance') || !(new \ReflectionMethod(self::class, 'getInstance'))->isStatic())
            throw new \RuntimeException("You must have getInstance() static method to use this trait");

        $pool = PaymentPool::getInstance();
        $name = self::getInstance();
        if($pool->getLink($name) === null){
            var_dump("link 없음");
            $pool->registerLink($name);
        }

        return $pool->getProvider($name);
    }

    /** @return float[] player name => money */
    public static function getAll() : array{
        return self::getPaymentProvider()->getAll();
    }

    /**
     * @param Player|string $player
     *
     * @return bool If player's data was exists
     */
    public static function exists($player) : bool{
        return self::getPaymentProvider()->exists($player);
    }

    /**
     * @param Player|string $player
     * @param float         $value init value
     *
     * @return bool If player's data was created
     */
    public static function create($player, float $value) : bool{
        return self::getPaymentProvider()->create($player, $value);
    }

    /**
     * @param Player|string $player
     *
     * @return float|null If player's data was exists return null, else return player's money
     */
    public static function get($player) : ?float{
        return self::getPaymentProvider()->get($player);
    }

    /**
     * @param Player|string $player
     * @param float         $value
     */
    public static function set($player, float $value) : void{
        self::getPaymentProvider()->set($player, $value);
    }

    /**
     * @param Player|string $player
     * @param float         $value
     *
     * @return float|null If player's data was exists return null, else return result money
     */
    public function increase($player, float $value) : ?float{
        return self::getPaymentProvider()->increase($player, $value);
    }

    /**
     * @param Player|string $player
     * @param float         $value
     *
     * @return float|null If player's data was exists return null, else return result money
     */
    public static function decrease($player, float $value) : ?float{
        return self::getPaymentProvider()->decrease($player, $value);
    }
}
