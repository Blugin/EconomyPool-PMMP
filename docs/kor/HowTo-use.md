<p align="right">  
  <a href="https://github.com/Blugin/PaymentPool/blob/master/docs/eng/HowTo-use.md">  
    <img src="https://img.shields.io/static/v1?label=read%20in&message=English&color=success">  
  </a>  
</p>  
  
## :book: 어떻게 사용하나요? - 사용자편  
  
### 명령어 사용 방법
##### `/payment <Set | Payments | Links | Install>`  
> 모든 명령어는 마인크래프트의 자동완성 기능을 지원합니다  
<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/docs/in-game-command-screenshot.png" width="100%">
----------  
<br>  
 
### /payment Set <링크 이름: `Link`> <결제수단 이름: `Payment`>  
- 각 링크(플러그인)에 어떤 결제 수단을 연결할지 설정하는 명령어입니다  
> `PayShop`이라는 상점 플러그인을 `economyapi`로 연결하고 싶을 경우 :  
````  
/payment set PayShop economyapi  
````  
  
- 기본 결제수단의 링크 이름은 `@`입니다  
- 기본 결제수단이 설정이 되어있지 않은 경우, 랜덤한 결제수단으로 연결되기 때문에 여러 결제수단이 있는 경우 설정해야합니다  
> `economyapi`가 기본 결제수단인 경우 :  
````  
/payment set @ economyapi  
````  
<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/docs/command-set-demo.png" width="100%">
----------  
<br>  
  
#### /payment Payments [페이지: `int`]  
- 등록되어있는 결제수단 목록을 표시하는 명령어입니다  
<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/docs/command-payments-demo.png" width="100%">
----------  
<br>  
  
#### /payment Links [페이지: `int`]  
- 등록되어있는 링크 목록을 표시하는 명령어입니다 (대체로 플러그인 이름)  
<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/docs/command-links-demo.png" width="100%">
----------  
<br>  
  
#### /payment Install  
- 서버에 있는 결제수단 플러그인이 등록되어있는 경우 자동으로 설치하는 명령어입니다  
<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/docs/command-install-demo.png" width="100%">  
  