<?php

namespace App\Http\ServicesLayer\Api\WhatsAppServices;

class WhatsAppService
{

    public function sendWhatsappNotification(int $mobile, string $message)
    {     

        $url = "https://whats.evyx.net/api/send?number=20$mobile&type=text&message=$message&instance_id=66C8330FBB75B&access_token=6474866802b9e";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $responseObj = json_decode($response, true);

        if(isset($responseObj['status']) && $responseObj['status'] == "success"){
            return true;
        }else{
            return false;
        }

        // {
        //     "status": "success", https://clincher.evyx.xyz/public/
        //     "message": {
        //         "key":{"remoteJid":"201201867608@c.us","fromMe":true,"id":"BAE501E2DEAB698E"},
        //         "message":{"extendedTextMessage":{"text":"code to verifay your mobile: 1122"}},
        //         "messageTimestamp":"1714586292"
        //     }
        // }
    }
    
}