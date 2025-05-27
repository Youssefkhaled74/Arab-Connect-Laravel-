<?php

namespace App\Http\ServicesLayer\Api\FirebaseServices;

use Illuminate\Support\Facades\DB;

class FirebaseService
{

    public function sendNotifiAll($title, $message)
    {
        $headings = array(
            "en" => "$title"
        );
        $content = array(
            "en" => "$message"
        );
        $fields = array(
            'app_id' => env('ONESIGNAL_APP_KEY_ID'),
            'included_segments' => array('All'),
            'contents' => $content,
            'data' => array('Screen' => 'Notification'),
            'headings' => $headings
        );
    
        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);
        $ResetKey = env('ONESIGNAL_APP_RESET_ID');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', "Authorization: Basic $ResetKey"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        // var_dump($response);
    }

    // array:7 [
    //     0 => "dc1e5f48-24cb-42c0-a813-cff1ffddfb83"
    //     1 => "afc12f25-444a-47fd-a02c-41272d677c2b"
    //     2 => "0000-00000-00000-000000"
    //     3 => "07d252b7-ba5f-449e-b1d7-45924ae10558"
    //     4 => "0000-00000-00000-000000"
    //     5 => "a4df3f54-5477-43b4-826c-4df85c4a7308"
    //     6 => "c71cd7e9-501e-4ab0-a8cb-5a3c85c972a9"
    // ]
    
    public function sendNotifi(string $title, string $message, array $palyerids, $url = "", $groupID, $adventureID)
    {
        $headings = array(
            "en" => "$title"
        );
        $content = array(
            "en" => "$message"
        );
        $fields = array(
            'app_id' => env('ONESIGNAL_APP_KEY_ID'),
            // 'include_player_ids' => ['7566b1f1-87fa-4553-a375-a4c711e0b749'],
            'include_player_ids' => $palyerids,
            'contents' => $content,
            'headings' => $headings,
            'data' => array(
                'groupID' => $groupID,
                'adventureID' => $adventureID,
            ),
            'url' => $url
        );
        $fields = json_encode($fields);

        $ResetKey = env('ONESIGNAL_APP_RESET_ID');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', "Authorization: Basic $ResetKey"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        var_dump($response);

    }

    public function storeNotifications(string $title, string $message, array $adventureMembers, int $groupId, int $adventureId = null)
    {

        if (!is_null($adventureMembers)) {

            foreach ($adventureMembers as $index) {
                $result [] = [
                    'title' => $title,
                    'message' => $message,
                    'user_id' => $index,
                    'group_id' => $groupId,
                    'adventure_id' => $adventureId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (count($result) > 0) {
                DB::table('notifications')->insert($result);
            }
        }

    }

}
