<?php

session_start();

function convertImageUrlToBase64($image_url)
{
    // Get the image data from the URL
    $image_data = file_get_contents($image_url);

    // Check if the image was successfully retrieved
    if ($image_data === false) {
        throw new Exception("Could not retrieve image from URL.");
    }

    // Get the MIME type of the image to include in the base64 string
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $image_data);
    finfo_close($finfo);

    // Encode the image data to base64
    $base64_image = base64_encode($image_data);

    // Return the base64 string with the MIME type prefix
    return 'data:' . $mime_type . ';base64,' . $base64_image;
}








function getFileFromSlack($file_id, $token)
{
    $url = "https://slack.com/api/files.info";
    $response = file_get_contents($url, false, stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer $token"
        ]
    ]));
    return json_decode($response, true);
}



function DownloadFile($url, $token)
{


    $dir = "files/";
    $handle = curl_init($url);
    // extracts the filename from the url 
    $filename = basename($url);
    $fp=fopen("exe.log","w+");
    fwrite($fp,$filename);

    $_SESSION["File_name"]=$filename;

    $save_location = $dir . $filename;
   
    $fp_location = fopen($save_location, 'wb');

    curl_setopt($handle, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ]);
    curl_setopt($handle, CURLOPT_FILE, $fp_location);


    // Perform a cURL session
    curl_exec($handle);
    curl_close($handle);
    fclose($fp);
}


function sendMessageToSlack($channel, $message, $token)
{
    $post_fields = json_encode([
        "channel" => $channel,
        "text" => $message
    ]);
    $url = "https://slack.com/api/chat.postMessage";

    $handle = curl_init($url);

    curl_setopt($handle, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ]);

    curl_setopt($handle, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);


    $response = curl_exec($handle);
    curl_close($handle);
    if (curl_errno($handle)) {
        throw new Exception(message: curl_error($handle));
    }
}

function getFileInfo($file_id, $token)
{
    $url = "https://slack.com/api/files.info?file=$file_id";
    $handle = curl_init($url);

    curl_setopt($handle, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ]);

    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($handle);

    curl_close($handle);
     $fp=fopen("exe.log","r+");
     fwrite($fp,$response);

    return $response;
}




// todo: create openAi function to send image with text to

function openAi($prompt)
{
    $url = "https://api.openai.com/v1/chat/completions";
    $base64_image=convertImageUrlToBase64(__DIR__."\\files\\".$_SESSION["File_name"]);
    //   $fp= fopen("exe.log","r+");
//    fwrite( $fp,$base64_image);
    $data = [
        "model" => "gpt-4o",
        "messages" => [
            [
                "role" => "user",
                "content" => [
                    [
                        "type" => "text",
                        "text" => $prompt
                    ],
                    [
                        "type" => "image_url",
                        "image_url" => [
                            "url" => $base64_image
                        ]
                    ]
                ]
            ]
        ],
        "max_tokens" => 2048
    ];

    $handle = curl_init($url);

    curl_setopt($handle, CURLOPT_HTTPHEADER, [

        "Content-Type: application/json",
        "Authorization: Bearer " . $_ENV["OPENAI_API_KEY"]

    ]);

    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_POST, true);

    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
    $response=curl_exec($handle);
    curl_close($handle);
 
    return $response;
}
