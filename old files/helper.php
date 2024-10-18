<?php

// session_start();
// /**
//  * *
//  * convert image file to basedx64
//  * @param string $image_url
//  * @throws \Exception
//  * @throws \FileNotAllowedException
//  * @return string
//  */
// function convertImageUrlToBase64(string $image_url)
// {
   
//     $image_data = file_get_contents($image_url);

//     if ($image_data === false) {
//         throw new Exception("Could not find file");
//     }

    
//     $finfo = finfo_open(FILEINFO_MIME_TYPE);

 

//     $mime_type = finfo_buffer($finfo, $image_data);
     
//     finfo_close($finfo);

//     $base64_image = base64_encode($image_data);


//     return 'data:' . $mime_type . ';base64,' . $base64_image;
// }








// /**
//  * this function get the file object from slack
//  * @param mixed $file_id
//  * @param mixed $token slack bot  token
//  * @return array
//  */
// function getFileFromSlack($file_id, $token)
// {
//     $url = "https://slack.com/api/files.info";
//     $response = file_get_contents($url, false, stream_context_create([
//         'http' => [
//             'header' => "Authorization: Bearer $token"
//         ]
//     ]));
//     return json_decode($response, true);
// }


// /**
//  * Download the file from slack and store it in files folder and set a file name session
//  * @param mixed $url
//  * @param mixed $token
//  * @return void
//  */
// function DownloadFile($url, $token)
// {
//     $dir = "files/";
//     $handle = curl_init($url);

//     // extracts the filename from the url 
//     $filename = basename($url);

//     $fp=fopen("exe.log","w+");

//     fwrite($fp,$filename);

//     $_SESSION["File_name"]=$filename;


//     $save_location = $dir . $filename;
   
//     $fp_location = fopen($save_location, 'wb');

//     curl_setopt($handle, CURLOPT_HTTPHEADER, [
//         'Content-Type: application/json',
//         'Authorization: Bearer ' . $token,
//     ]);
//     curl_setopt($handle, CURLOPT_FILE, $fp_location);


   
//     curl_exec($handle);
//     curl_close($handle);
//     fclose($fp);
// }

// /**
//  * send message to a slack channel 
//  * @param mixed $channel
//  * @param mixed $message
//  * @param mixed $token
//  * @throws \Exception
//  * @return void
//  */
// function sendMessageToSlack($channel, $message, $token)
// {
//     $post_fields = json_encode([
//         "channel" => $channel,
//         "text" => $message
//     ]);
//     $url = "https://slack.com/api/chat.postMessage";

//     $handle = curl_init($url);

//     curl_setopt($handle, CURLOPT_HTTPHEADER, [
//         'Content-Type: application/json',
//         'Authorization: Bearer ' . $token,
//     ]);

//     curl_setopt($handle, CURLOPT_POSTFIELDS, $post_fields);
//     curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);


//     $response = curl_exec($handle);
//     curl_close($handle);
//     if (curl_errno($handle)) {
//         throw new Exception(message: curl_error($handle));
//     }
// }



// /**
//  * this function get the file object from slack
//  * @param mixed $file_id
//  * @param mixed $token slack bot  token
//  * @return string 
//  */
// function getFileInfo($file_id, $token)
// {
//     $url = "https://slack.com/api/files.info?file=$file_id";
//     $handle = curl_init($url);

//     curl_setopt($handle, CURLOPT_HTTPHEADER, [
//         'Content-Type: application/json',
//         'Authorization: Bearer ' . $token,
//     ]);

//     curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

//     $response = curl_exec($handle);

//     curl_close($handle);
//      $fp=fopen("exe.log","r+");
//      fwrite($fp,$response);

//     return $response;
// }





// /**
//  * send the file and prompt to open ai
//  * @param mixed $prompt
//  * @return bool|string
//  */
// function openAi($prompt)
// {
//     $url = "https://api.openai.com/v1/chat/completions";
//     $base64_image=convertImageUrlToBase64(__DIR__."\\files\\".$_SESSION["File_name"]);
//     $data = [
//         "model" => "gpt-4o",
//         "messages" => [
//             [
//                 "role" => "user",
//                 "content" => [
//                     [
//                         "type" => "text",
//                         "text" => $prompt
//                     ],
//                     [
//                         "type" => "image_url",
//                         "image_url" => [
//                             "url" => $base64_image
//                         ]
//                     ]
//                 ]
//             ]
//         ],
//         "max_tokens" => 2048
//     ];

//     $handle = curl_init($url);

//     curl_setopt($handle, CURLOPT_HTTPHEADER, [

//         "Content-Type: application/json",
//         "Authorization: Bearer " . $_ENV["OPENAI_API_KEY"]

//     ]);

//     curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
//     // curl_setopt($handle, CURLOPT_POST, true);

//     curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
//     $response=curl_exec($handle);
//     curl_close($handle);
 
//     return $response;
// }
