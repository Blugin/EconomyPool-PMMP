<p align="right">  
  <a href="https://github.com/Blugin/PaymentPool/blob/master/doc/kor/HowToUse.md">  
    <img src="https://img.shields.io/static/v1?label=%ED%95%9C%EA%B5%AD%EC%96%B4&message=%EB%A1%9C+%EC%9D%BD%EA%B8%B0&labelColor=success">  
  </a>  
</p>  
  
# :book: How to use? - for user  
  
## Plugins you need to use
### [<img src="https://ghcdn.rawgit.org/Blugin/libCommand/master/icon.png" width="20px">**libCommand**](https://github.com/Blugin/libCommand)  
- It is a library plug-in that manages commands by OOP them, and supports automatic completion and command setting  
- Used to add `/ payment` command  
  
### [<img src="https://ghcdn.rawgit.org/Blugin/Payments/master/icon.png" width="20px">**Payments**](https://github.com/Blugin/Payments)  
- `PaymentProvider` plugins that link to the payment method plugin used in the server  
- You need to get the plugin directly from here and apply it  
> However, we know this is a cumbersome way, so we support the automatic install feature!  
  
  
----------  
  
  
## How to use commands? `/payment <Set | Payments | Links | Install>`  
> All commands support Minecraft's autocomplete feature  
  
### /payment Set <Link name: `Link`> <Payment name: `Payment`>  
- This command sets which payment method to connect to each link (plug-in)  
> If you want to connect a shop plugin called `PayShop` to `economyapi`:  
````  
/payment set PayShop economyapi  
````  
  
- The link name for the default payment method is `@`  
- If the default payment method is not set, it is connected to a random payment method, so if there are multiple payment methods, you must set it  
> If `economyapi` is your default payment method:  
````  
/payment set @ economyapi  
````  
  
#### /payment Payments [Page: `int`]  
- This command displays the list of registered payment methods  
  
#### /payment Links [Page: `int`]  
- This command displays the list of registered links (In general, the plugin)  
  
#### /payment Install  
- This command is automatically installed when the payment method plug-in on the server is registered.
<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/docs/README/docs/command-install-demo.png" width="100%">  
  