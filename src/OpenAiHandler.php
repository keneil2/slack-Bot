<?php
namespace SLACKBOT;
class OpenAiHandler extends CurlHandler
{
    protected $open_ai_endpoint = "https://api.openai.com/v1/chat/completions";
    protected $headers;
    public function __construct()
    {
        $this->handle =  curl_init($this->open_ai_endpoint);
        if (!isset($_ENV["OPENAI_API_KEY"])) {
            throw new \Exception("no open ai Api key provided");
        }
        $this->headers = [


            "Content-Type: application/json",
            "Authorization: Bearer " . $_ENV["OPENAI_API_KEY"]


        ];
    }
    public function sendPrompt($prompt,$base64_image)
    {
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
     $this->setSlackHeaders($this->headers);
     $this->setPOSTInputFeilds($data);
    return  $this->dispatcher(); 
    }
}