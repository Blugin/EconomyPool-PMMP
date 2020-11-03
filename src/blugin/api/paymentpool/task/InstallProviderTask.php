<?php

namespace blugin\api\paymentpool\task;

use blugin\api\paymentpool\PaymentPool;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

use const pocketmine\BASE_VERSION;

final class InstallProviderTask extends AsyncTask{
    public function onRun() : void{
        $urlPrefix = "https://raw.githubusercontent.com/Blugin/Payments/master/";

        $data = $this->getFileFrom("{$urlPrefix}list.json");
        if(!is_string($data))
            return;

        $result = [];
        foreach(json_decode($data, true) as $className => $providerName){
            $scriptData = $this->getFileFrom(sprintf("{$urlPrefix}%s-%s.x.x.php", $providerName, BASE_VERSION[0]));
            if(is_string($scriptData)){
                $result[$providerName] = [$className, $scriptData];
            }
        }
        $this->setResult($result);
    }

    public function onCompletion(Server $server) : void{
        $result = $this->getResult();
        if($result !== null){
            $providersPath = PaymentPool::getInstance()->getProvidersPath();
            $pluginManager = Server::getInstance()->getPluginManager();
            foreach($result as $providerName => $value){
                [$className, $scriptData] = $value;
                if(!class_exists("\\" . $className))
                    continue;

                if($pluginManager->getPlugin("Payment" . $providerName) !== null)
                    continue;

                $scriptPath = $providersPath . $providerName . ".php";
                if(file_put_contents($scriptPath, $scriptData) === false)
                    continue;

                $plugin = $pluginManager->loadPlugin($scriptPath);
                $pluginManager->enablePlugin($plugin);
            }
        }
    }

    protected function getFileFrom(string $filename) : ?string{
        try{
            return file_get_contents($filename, false, stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ]
            ]));
        }catch(\Exception $e){
            return null;
        }
    }
}