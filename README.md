<p align="center"> <img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/icon.png" width="50%"> </p>  
  
# PaymentPool  
### The bridge that connects all payments plugins!
<p align="right">  
  <a href="https://github.com/Blugin/PaymentPool/blob/master/README_KOR.md">  
    <img src="https://img.shields.io/static/v1?label=%ED%95%9C%EA%B5%AD%EC%96%B4&message=%EB%A1%9C+%EC%9D%BD%EA%B8%B0&labelColor=success">  
  </a>  
</p>  
    
[![poggit-build](https://poggit.pmmp.io/ci.shield/Blugin/PaymentPool/PaymentPool)](https://poggit.pmmp.io/ci/Blugin/PaymentPool/PaymentPool)
[![license](https://img.shields.io/github/license/Blugin/PaymentPool.svg)](https://github.com/Blugin/PaymentPool/blob/master/LICENSE)
[![hits](https://views.whatilearened.today/views/github/Blugin/PaymentPool.svg)](http://hits.dwyl.com/Blugin/PaymentPool)
  
✔️ All payment method plugins are integrated and managed!  
✔️ You can set which payment method to connect to each plugin!  
  
<br>  
  
## :book: What is the feature?
This plugin is a library plugin that integrates and manages several payment method plugins into one  
  
First of all, `payment method` in this article is a collective name for plugins that can be used for payment  
> Economy plugin (EconomyAPI, etc.)  
> Cache plugin (Cash, SCash, etc.)  
> Point plug-in (PointAPI, etc.)  
  
<img src="https://ghcdn.rawgit.org/Blugin/PaymentPool/master/docs/request-flow-diagram.png" width="100%">
Payment method is connected to PaymentPool and managed, and it works by requesting PaymentPool from other plugins  
User can set which payment method is connected for each plugin  
  
----------  
  
#### User Benefits  
You don't have to worry about which payment plugin is link with the plugin you want to use!  
The same is true even if you use multiple payment methods such as economy, cash, and points on the server at once  
Because you can set which payment method to connect to each plugin with a command :)  

For more detailed explanation, see [**[:book: How to use? - for user]**](https://github.com/Blugin/PaymentPool/blob/master/doc/eng/HowTo-use.md)  
  
----------  
  
#### Developers Benefits  
Now you don't have to worry about which payment plugin to connect your plugin to!  
Just use with PaymentPool, and the user will choose and use the payment method :)  
It is also possible to link multiple payment methods at in once.  
  
In addition, if you use this plugin, you can escape from GPL licenses such as `EconomyAPI` :)  

       
<br>  
  
## :file_folder: Target software:  
**This plugin officially only works with [Pocketmine-MP `API 3.x.x`](https://github.com/pmmp/PocketMine-MP/tree/stable)**
> **If you use [**Pocketmine-MP** `API 4.x.x`](https://github.com/pmmp/PocketMine-MP/tree/master),**  
> **You need to build from the source directly from the [`api4.0.0`](https://github.com/Blugin/PaymentPool/tree/api4.0.0) branch.**
  
<br>  
  
## :wrench: Installation
1) Download `.phar` from [poggit](https://poggit.pmmp.io/ci/Blugin/PaymentPool/PaymentPool)  
2) Move dowloaded `.phar` file to your server's **/plugins/** folder  
3) Restart the server (or execute `/reload` command)  
  
<br>  
  
## :books: API Usage
- [:book: How to use it?](https://github.com/Blugin/PaymentPool/blob/master/doc/eng/HowToUse.md)
  
<br>  
  
## :memo: License  
> You can check out the full license [here](https://github.com/Blugin/PaymentPool/blob/master/LICENSE)  
  
This project licensed under the terms of the **LGPL3.0 LICENSE**  
