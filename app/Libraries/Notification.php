<?php
namespace App\Libraries;

class Notification{
    public function sendNotification($tokens, $title, $msg){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "registration_ids": ' . json_encode($tokens) . ',
            "notification": {
                "title": "' . $title . '",
                "body": "' . $msg . '"
            }
        }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: key=AAAA8o0O1Wg:APA91bFVnYzfYuZ3OWu8uxrVoFW7Rk2SH7SDO5FnjhRpYFWex56uXPuk9E1SIA_iy3fnbTmh2kYk15jnon7kHcy6uMSjzlK1gbkx2PNUbkFe9yIepuMtDsqQxhEZvxH69lq-HRi-2x7t',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return $response;
    }
}