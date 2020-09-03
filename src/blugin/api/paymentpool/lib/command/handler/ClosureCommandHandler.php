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

namespace blugin\api\paymentpool\lib\command\handler;

use blugin\api\paymentpool\lib\command\overload\Overload;
use pocketmine\command\CommandSender;
use pocketmine\utils\Utils;

class ClosureCommandHandler implements ICommandHandler{
    /** @var \Closure */
    protected $closure;

    public function __construct(\Closure $closure){
        Utils::validateCallableSignature(function(CommandSender $sender, array $args, Overload $overload) : bool{
            return true;
        }, $closure);

        $this->closure = $closure;
    }

    /**
     * @param mixed[] $args name => value
     */
    public function handle(CommandSender $sender, array $args, Overload $overload) : bool{
        return ($this->closure)($sender, $args, $overload);
    }
}