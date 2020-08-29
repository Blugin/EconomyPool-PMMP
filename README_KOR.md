<p align="center"> <img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/docs/README/icon.png" width="50%"> </p>  
  
# PaymentPool  
### 모든 결제수단을 관리하는 플러그인
<p align="right">  
  <a href="https://github.com/Blugin/PaymentPool/blob/master/README_KOR.md">  
    <img src="https://img.shields.io/static/v1?label=read%20in&message=English&color=success">  
  </a>  
</p>  
    
[![poggit-build](https://poggit.pmmp.io/ci.shield/Blugin/PaymentPool/PaymentPool)](https://poggit.pmmp.io/ci/Blugin/PaymentPool/PaymentPool)
[![license](https://img.shields.io/github/license/Blugin/PaymentPool.svg)](https://github.com/Blugin/PaymentPool/blob/master/LICENSE)
[![hits](https://views.whatilearened.today/views/github/Blugin/PaymentPool.svg)](http://hits.dwyl.com/Blugin/PaymentPool)
  
✔️ 모든 결제수단류 플러그인을 통합해서 관리합니다!  
✔️ 각 플러그인에 어떤 결제수단을 연결할지 설정할 수 있습니다!  
  
<br>  
  
## :book: 기능이 무엇인가요?  
이 플러그인은 여러 가지 결제수단 플러그인들을 하나로 통합하고 관리하는 라이브러리 플러그인입니다  
  
먼저 이 글에서 말하는 `결제수단`은 결제에 사용할 수 있는 플러그인들 통칭하는 명칭입니다  
> 경제 플러그인 (EconomyAPI 등)  
> 캐쉬 플러그인 (Cash,SCash 등)  
> 포인트 플러그인 (PointAPI 등)  
  
<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/docs/request-flow-diagram.png" width="100%">
결제수단이 PaymentPool에 연결되어 관리되고, 다른 플러그인에서 PaymentPool에게 요청하는 방식으로 작동됩니다  
사용자는 각 플러그인 마다 어떤 결제수단과 연결될 지 설정할 수 있습니다  
  
----------  
  
#### 사용자가 얻는 장점  
사용하려는 플러그인이 어떤 결제 플러그인과 연결되는 지 걱정할 필요가 없습니다!  
서버에서 경제,캐쉬,포인트 등의 여러 결제수단을 한번에 사용해도 마찬가지입니다  
각 플러그인에 어떤 결제수단을 연결할 지 명령어로 설정이 가능하기 때문이죠 :)  

더 자세한 설명은 [[**:book: 어떻게 사용하나요? - 사용자 편**]](https://github.com/Blugin/PaymentPool/blob/master/doc/kor/HowTo-use.md) 을 참고하세요   
  
----------  
  
#### 개발자가 얻는 장점  
이제 플러그인을 어떤 결제 플러그인과 연결해야 할지 고민하지 않아도 됩니다!  
그냥 PaymentPool과 연결하기만 하면, 사용자가 알아서 결제수단을 골라서 사용할 거에요 :)  
동시에 여러개의 결제수단을 연동하는 것도 충분히 가능해요  
  
추가로 이 플러그인을 사용하면 `EconomyAPI` 등의 GPL라이선스에서도 벗어날 수 있습니다 :) 

       
<br>  
  
## :file_folder: 대상 소프트웨어: 
**이 플러그인은 공식적으로 [Pocketmine-MP `API 3.x.x`](https://github.com/pmmp/PocketMine-MP/tree/stable) 에서만 작동합니다**
> **만약 당신이 [**Pocketmine-MP** `API 4.x.x`](https://github.com/pmmp/PocketMine-MP/tree/master) 을 사용한다면,**  
> **[`api4.0.0`](https://github.com/Blugin/PaymentPool/tree/api4.0.0) 브랜치에서 직접 소스를 빌드해야합니다.**
  
<br>  
  
## :wrench: 설치
1) [Poggit](https://poggit.pmmp.io/ci/Blugin/PaymentPool/PaymentPool) 에서 `.phar`을  받으세요  
2) 다운받은 `.phar`파일을 당신의 서버의 **/plugins/** 폴더에 넣으세요  
3) 서버를 재시작하세요 (혹은 `/reload` 명령어를 실행하세요)  
  
<br>  
  
## :memo: 라이센스 
> 라이센스 전문은 [여기](https://github.com/Blugin/PaymentPool/blob/master/LICENSE) 에서 확인할 수 있습니다  
  
이 프로젝트는 **LGPL3.0 LICENSE** 라이센스 조건에 따라 라이센스가 부여됩니다
