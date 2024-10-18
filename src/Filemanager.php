<?php
namespace SLACKBOT;
class Filemanager extends FilterText
{
    public function getLatestPrompt(): string|bool
    {
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "prompts.txt")) {
            $contents_array = file(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "prompts.txt");
            return $contents_array[0];
        } else {
            throw new \Exception(" prompt file not found");
        }

    }

    public function LogInfo($info)
    {
        $s_info= $this->sanitizeInput($info);
        $fp = fopen(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "info.log", "r+");
        if (flock($fp, LOCK_EX)) {

            fwrite($fp, $s_info);

            flock($fp, LOCK_UN);
        }

    }

    public function writePromptTofile($prompt)
    {
        $s_prompt= $this->sanitizeInput($prompt);
        $fp = fopen(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "prompts.txt", "w+");
        if (flock($fp, LOCK_EX)) {

            fwrite($fp, $s_prompt);

            flock($fp, LOCK_UN);
        }

    }


    public function LogErrors($error)
    {
        $fp = fopen(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "error.log", "w+");
        if (flock($fp, LOCK_EX)) {

            fwrite($fp, $error);

            flock($fp, LOCK_UN);
        }

    }
    public function convertImageToBase64(){
        $image_data = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."/files/".$_SESSION["File_name"]);

        if ($image_data === false) {
            throw new \Exception("Could not find file");
        }
    
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
    
     
    
        $mime_type = finfo_buffer($finfo, $image_data);
         
        finfo_close($finfo);
    
        $base64_image = base64_encode($image_data);
    
    
        return 'data:' . $mime_type . ';base64,' . $base64_image;
    }
}