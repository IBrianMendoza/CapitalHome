<?php

function sw_win_getDirContents($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path) && substr_count($value, '.php') == 1 &&  substr_count($value, 'translate_generator.php') == 0  ) {
            $results[] = $path;
        } else if(is_dir($path) && $value != "." && $value != "..") {
            sw_win_getDirContents($path, $results);
            //$results[] = $path;
        }
    }

    return $results;
}

$files = sw_win_getDirContents('.');

foreach($files as $file)
{
    $content = file_get_contents($file);
    
    $lastPos=0;
    $count=0;
    $count_rep=0;
    $needle = '__(';
    while (($lastPos = strpos($content, $needle, $lastPos)) !== false) {
        $to = strpos($content, ')', $lastPos+1);
        $from = $lastPos+strlen($needle);
        $length = $to-$lastPos-strlen($needle);
        $code = substr($content, $from, $length);
        
        $search = substr($content, $lastPos, $to-$lastPos+1);
        //$replace = '<iframe width="420" height="315" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen></iframe>';

        if(substr_count($search, "'") == 0)
            echo $search.'???????????????????<br />';
        else if(substr_count($search, "sw_win") == 1)
        {
            
        }
        else if(substr_count($search, "'") > 3)
            echo $search.'!!!!!!!!!!!!!!!!!!!!!!!!<br />';
        else
        {
            $replace = str_replace(')', ', \'sw_win\')',$search);
            $count_rep++;
            
            echo $search.'  ==>>  '.$replace.'<br />';
            $content = str_replace($search, $replace, $content);
        }
        
        
        
        $lastPos = $lastPos + strlen($needle);
        $count++;
    }
    
    if($count > 0)
    {
        echo $file.'---------------------<br />';
    }
    
    if($count_rep > 0)
    {
        echo $file.'---------------------++++++++++<br />';
        //file_put_contents($file, $content);
    }
        
}





?>