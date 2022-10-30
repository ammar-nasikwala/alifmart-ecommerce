<?php 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => " {\r\n\"title\":\"Notification\",\r\n\"notification\": {\r\n    \"title\": \"Seller_TrackPayment\",\r\n  
  \"text\": \"Payment status\",\r\n    \"icon\": \"ic_push\",\r\n    \"click_action\":\"Seller_TrackPayment\"\r\n  },\r\n    
  \"data\": {\r\n    \"activity\": \"Seller_TrackPayment\",\t\r\n    \"id_offer\": \"1758\",\r\n     
  \"title1\": \"Company-Name2\",\r\n     \"text1\": \"Data\"\r\n  },\r\n    
  \"registration_ids\":[\"dJBsZlu1bKQ:APA91bErxKhPm-rqPhFeeeL-EGP7ibsSpWE5P2tOYn9APsrndfp5_jkz8gy_9Cqiaumy5dfSqTcgQD5_JKSus-igZ1DgLgO2Z_ibLoXLuQDYR7jQo3sCfGORMJuCRkT7iprdrTZVnKaV\"]\r\n}",
  CURLOPT_HTTPHEADER => array(
    "authorization: key=AIzaSyDUwPIUb32S2aT0cUKoDIpY1TfPiCCSBI8",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: f15e9226-1412-cbd4-43f3-eb34008a3bf3"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}



?>