<p align="right">  
  <a href="https://github.com/Blugin/PaymentPool/blob/master/docs/kor/HowTo-dev.md">  
    <img src="https://img.shields.io/static/v1?label=%ED%95%9C%EA%B5%AD%EC%96%B4&message=%EB%A1%9C+%EC%9D%BD%EA%B8%B0&labelColor=success">  
  </a>  
</p>  
  
## :book: How to use? - for developer  
  
### Plugins you need to use  
#### [<img src="https://ghcdn.rawgit.org/Blugin/libCommand/master/icon.png" width="20px">**libCommand**](https://github.com/Blugin/libCommand)  
- It is a library plug-in that manages commands by OOP them, and supports automatic completion and command setting  
- Used to add `/ payment` command  
  
#### [<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/icon.png" width="20px">**PaymentPool**](https://github.com/Blugin/PaymentPool)  
- This is the main API plugin `PaymentPool` plugin  
  
  
----------  
  
  
#### :zap: How to register a payment  
> To register Payment, you need an object that implements `IPaymentProvider`  
> The best examples are in [<img src="https://ghcdn.rawgit.org/Blugin/Payments/master/icon.png" width="20px">**Payments**](https://github.com/Blugin/Payments)  
> The source below is part of the [EconomyAPIProvider](https://github.com/Blugin/Payments/blob/master/EconomyAPIProvider-3.x.x.php) source    
> ```php  
> PaymentPool::getInstance()->registerProvider(new class() implements IPaymentProvider{
>   public function getName() : string{
>       return "economyapi";
>   }
> 
>   public function getAll() : array{
>       return EconomyAPI::getInstance()->getAllMoney();
>   }
> 
>   public function exists($player) : bool{
>       return EconomyAPI::getInstance()->hasAccount($player);
>   }
> 
>   public function create($player, float $value) : bool{
>       if($this->exists($player))
>           return false;
> 
>       EconomyAPI::getInstance()->createAccount($player, null, $value);
>       return true;
>   }
> 
>   public function get($player) : ?float{
>       $result = EconomyAPI::getInstance()->myMoney($player);
>       if($result === false){
>           $result = null;
>       }
>       return $result;
>   }
> 
>   public function set($player, float $value) : void{
>       EconomyAPI::getInstance()->setMoney($player, $value);
>   }
> 
>   public function increase($player, float $value) : ?float{
>       EconomyAPI::getInstance()->addMoney($player, $value);
>       return $this->get($player);
>   }
> 
>   public function decrease($player, float $value) : ?float{
>       EconomyAPI::getInstance()->reduceMoney($player, $value);
>       return $this->get($player);
>   }
> }, ["onebone:economyapi", "economys"]);  
> ```  
  
<br>  
  
#### :sparkles: Quick use via `PaymentTrait`  
> Provided basic Trait to be used in `PluginBase` for quick use by developers  
> Add `PaymentTrait` to main class (class with `getInstance` method), Everything is ready  
> Both `Link` registration and `Provider` getting handled automatically  
> ```php  
> use blugin\api\paymentpool\PaymentPool;
> use blugin\api\paymentpool\traits\PaymentTrait;
> 
> class YourMainClass extends PluginBase{
>     use PaymentTrait;
> 
>     private static $instance;
> 
>     public static function getInstance() : self{
>         return self::$instance;
>     }
> 
>     public function onLoad(){
>         self::$instance = $this;
>     }
> 
>     public function onEnable(){
>         $playerName = "testplayer";
>         if(!self::exists($playerName)){
>             self::create($playerName, 1000);
>         }
> 
>         var_dump(self::get($playerName));           // float(1000)
>         var_dump(self::increase($playerName, 100)); // float(1100)
>     }
> }
> ```  
  
<br>  
  
#### :sparkles: Using `PaymentTrait` in a class other than `PluginBase`  
> This feature is trait, so you can edit it freely  
> You can also override `getPaymentProvider` and use it like this:  
> ```php
> use blugin\api\paymentpool\IPaymentProvider;
> use blugin\api\paymentpool\PaymentPool;
> use blugin\api\paymentpool\traits\PaymentTrait;
> 
> class PaymentAPI{
>     public const LINK_NAME = "AnyNameYouWant";
> 
>     use PaymentTrait {
>         //It is also possible to rename the method like this  
>         exists as existsAccount;
>         create as createAccount;
>         getAll as getAllMoney;
>         get as getMoney;
>         set as setMoney;
>         increase as addMoney;
>         decrease as reduceMoney;
>     }
> 
>     public static function getPaymentProvider() : ?IPaymentProvider{
>         if(PaymentPool::getInstance()->getLink(self::LINK_NAME) === null){
>             PaymentPool::getInstance()->registerLink(self::LINK_NAME);
>         }
>   
>         return PaymentPool::getInstance()->getProvider(self::LINK_NAME);
>     }
> }
> ```
> 
> ```php  
> class YourMainClass extends PluginBase{
>     public function onEnable(){
>         $playerName = "testplayer2";
>         if(!PaymentAPI::existsAccount($playerName)){
>             PaymentAPI::createAccount($playerName, 1000);
>         }
>   
>         var_dump(PaymentAPI::getMoney($playerName));         //float(1000)
>         var_dump(PaymentAPI::addMoney($playerName, 500));    //float(1500)
>         var_dump(PaymentAPI::reduceMoney($playerName, 300)); //float(1200)
>     }
> }
> ```  
  
<br>  
  
#### :zap: Accessing and using `PaymentPool` directly  
> PaymentPool can also be use like `PaymentPool::getInstance()->getProvider($name)`  
> 
> `getProvider()` require an object or string with `getName()` (Usually `PluginBase`)  
> This method returns the Provider according to the Link registered with that name  
> ```php  
> class YourMainClass extends PluginBase{
>     public function onLoad(){
>         // When using PaymentPool, please register and use Link if possible  
>         // Otherwise, the user cannot specify the payment method, and only the default payment method is used  
>         PaymentPool::getInstance()->registerLink($this);
>     }
> 
>     public function onEnable(){
>         $provider = PaymentPool::getInstance()->getProvider($this);
> 
>         $playerName = "testplayer";
>         if(!$provider->exists($playerName)){
>             self::create($playerName, 1000);
>         }
> 
>         var_dump($provider->get($playerName)); // float(1000)
>         var_dump($provider->increase($playerName, 100)); // float(1100)
>     }
> }
> ```  