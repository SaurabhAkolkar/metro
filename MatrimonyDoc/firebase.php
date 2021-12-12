<?php 

$API_ACCESS_KEY= 'AAAA0JIdHpc:APA91bGmvp_fMIu81v3ivfaL2bRAmKGYKDs3eIAmQLB632JsfyQPjUS0kvUucSRtH6xBI6OuSupjf_x-56mpeEDeEdcO_g8zMGaJSH91lfuARc7M53ZCgozWiSBp1kS96voD7AES_RFy';

        $registrationIds ="eUy57oqXxYs:APA91bHm-YtVtSQBcHBg1s9IOM9opiylGX2v-Muz8QPo3eSXyeiIrHIAEM4znqWDSqnS98kpPX9XWRgIF8smZDt3Eo-rzm3ndCREHm1DcttKwVbgmxlH_o1zaaYL5MdDWBfUcIBjBBlc";

        $msg = array
          (
            'body'    => "hello",
            'title'   => "title",
            'type'   => "70001",
            'data'   => " ",
            'icon'    => 'myicon',/*Default Icon*/
            'sound'   =>  'mySound'/*Default sound*/
        );
        $fields = array
        (
          'to' => $registrationIds,
          'notification'    => $msg
        );
        $headers = array
        (
          'Authorization: key=' . $API_ACCESS_KEY,
          'Content-Type: application/json'
        );
        #Send Reponse To FireBase Server    
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        print_r($result);
        curl_close( $ch );     

?>