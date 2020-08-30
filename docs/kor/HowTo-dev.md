<p align="right">  
  <a href="https://github.com/Blugin/PaymentPool/blob/master/docs/eng/HowTo-dev.md">  
    <img src="https://img.shields.io/static/v1?label=read%20in&message=English&color=success">  
  </a>  
</p>  
  
## :book: 어떻게 사용하나요? - 개발자편  
  
### 사용하기 위해 필요한 플러그인  
#### [<img src="https://ghcdn.rawgit.org/Blugin/libCommand/master/icon.png" width="20px">**libCommand**](https://github.com/Blugin/libCommand)  
- 명령어를 객체화해 관리하고, 자동 완성, 명령어 설정을 지원하는 라이브러리 플러그인입니다  
- `/payment` 명령어를 추가하는데 사용됩니다  
  
#### [<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/icon.png" width="20px">**PaymentPool**](https://github.com/Blugin/PaymentPool)  
- 메인 API플러그인인 `PaymentPool` 플러그인입니다  
  
<br>  
----------  
<br>  
  
#### :zap: 결제수단을 등록하는 방법  
> Payment를 등록하기 위해선 `IPaymentProvider`를 구현한 객체가 필요합니다  
> 가장 좋은 예제들은 [<img src="https://ghcdn.rawgit.org/Blugin/Payments/master/icon.png" width="20px">**Payments**](https://github.com/Blugin/Payments) 에 있습니다  
> 아래 소스는 [EconomyAPIProvider](https://github.com/Blugin/Payments/blob/master/EconomyAPIProvider-3.x.x.php) 의 소스 일부입니다  
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
  
----------  
<br>  
  
#### :sparkles: `PaymentTrait`를 사용해 빠르게 사용하기  
> 개발자의 빠른 사용을 위해 `PluginBase`에 사용할 기본 Trait을 제공합니다  
> `PaymentTrait`을 `getInstance` 메소드가 있는 클래스에 추가하면 모든 준비가 끝납니다  
> `Link`등록과 `Provider`얻어오기 등을 모두 자동으로 처리합니다   
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
  
----------  
<br>  
  
#### :sparkles: `PluginBase`가 아닌 클래스에서 `PaymentTrait`를 사용하기  
> 이 기능은 trait이기 때문에 마음 껏 수정이 가능합니다  
> `getPaymentProvider`를 오버라이드하고 아래처럼 사용할 수도 있습니다  
> ```php
> use blugin\api\paymentpool\IPaymentProvider;
> use blugin\api\paymentpool\PaymentPool;
> use blugin\api\paymentpool\traits\PaymentTrait;
> 
> class PaymentAPI{
>     public const LINK_NAME = "AnyNameYouWant";
> 
>     use PaymentTrait {
>         //다음과 같이 메서드의 이름을 바꿀 수도 있습니다  
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
  
----------  
<br>  
  
#### :zap: `PaymentPool`에 직접 접근하여 사용하기
> PaymentPool은 `PaymentPool::getInstance()->getProvider($name)`같은 방식으로 사용하는 것도 가능합니다  
> 
> `getProvider()`에는 `getName()`이 있는 객체나 문자열이 필요합니다 (대체로 `PluginBase`)  
> 이 메소드는 해당 이름으로 등록된 Link에 따라 Provider를 반환합니다  
> ```php  
> class YourMainClass extends PluginBase{
>     public function onLoad(){
>         // PaymentPool을 사용할 땐 되도록이면 Link를 등록하여 사용해주세요  
>         // 그렇지 않으면 사용자가 결제수단을 지정할 수 없고, 기본 결제수단만 사용합니다  
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