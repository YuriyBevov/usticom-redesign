<?php

function debug($data) {
    echo '<pre>' . print_r($data, 1) . '</pre>';
};

AddEventHandler("main", "OnEndBufferContent", "ChangeMyContent");
 
function ChangeMyContent(&$content)
{
 
    if(isset($_GET['PAGEN_1']))
    {
        $page=(int)$_GET['PAGEN_1'];
        
        $pattern = '/(.*?)<title[^>]*>(.*?)\n?\n?<\/title>(.*)/s';
        $replacement = '$1<title>$2 &mdash; cтраница ' .$page. '</title>$3';
        $content= preg_replace($pattern, $replacement, $content);

        $pattern = '/(.*?)<meta name="description" content="(.*?)\n?\n?"\s?\/>(.*)/s';
        $replacement = '$1<meta name="description" content="$2 &mdash; cтраница ' .$page. '">$3';
        $content= preg_replace($pattern, $replacement, $content);
    }
 
}

