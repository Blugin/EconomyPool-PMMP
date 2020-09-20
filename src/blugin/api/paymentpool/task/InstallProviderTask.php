<?php

namespace blugin\api\paymentpool\task;

use blugin\api\paymentpool\PaymentPool;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

use const pocketmine\BASE_VERSION;

final class InstallProviderTask extends AsyncTask{
    public const URL_PREFIX = "https://raw.githubusercontent.com/Blugin/Payments/master/";
    public const URL_LIST_JSON = self::URL_PREFIX . "list.json";
    public const FILENAME_FORMAT = "%s-%s.x.x.php";

    public function onRun() : void{
        $data = $this->getFileFrom(self::URL_LIST_JSON);
        if(!is_string($data))
            return;

        $result = [];
        foreach(json_decode($data, true) as $className => $providerName){
            if(!class_exists($className))
                continue;

            $scriptData = $this->getFileFrom(sprintf(self::URL_PREFIX . self::FILENAME_FORMAT, $providerName, BASE_VERSION[0]));
            if(is_string($scriptData)){
                $result[$providerName] = $scriptData;
            }
        }
        $this->setResult($result);
    }

    public function onCompletion(Server $server) : void{
        $result = $this->getResult();
        if($result !== null){
            $providersPath = PaymentPool::getInstance()->getProvidersPath();
            $pluginManager = Server::getInstance()->getPluginManager();
            foreach($result as $providerName => $scriptData){
                if($pluginManager->getPlugin("Payment" . $providerName) !== null)
                    continue;

                $scriptPath = $providersPath . $providerName . ".php";
                if(file_put_contents($scriptPath, $scriptData) === false)
                    continue;

                $pluginManager->loadPlugin($scriptPath);
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