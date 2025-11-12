<?php
    date_default_timezone_set('Asia/Shanghai');
    if($argc == 0)
    {
        exit('Nothing to copy');
    }

    define('DS', DIRECTORY_SEPARATOR);

    $cur_time = date("YmdHi");
    $source_dir = 'D:'.DS.'MYOA'.DS.'webroot';
    $exp_dir = 'D:'.DS.'oa_export'.DS.'export'.DS.'OA_V'.$cur_time;

    function ExportOneFile($path)
    {
        global $source_dir,$exp_dir;

        $final_source = $source_dir.DS.$path;
        $final_dest = $exp_dir.DS.$path;

        $final_dest_dir = dirname($final_dest).DS;
        if(!is_dir($final_dest_dir))
        {
            mkdir($final_dest_dir,0777,true);
        }
        return @copy($final_source,$final_dest);
    }

    foreach($argv as $index=>$path)
    {
        if($index === 0)
        {
            continue;
        }
        if(ExportOneFile($path))
        {
            echo $index.' : '.$path." exported." . PHP_EOL;
        }
    }

    echo PHP_EOL. "All Complete. Please go to {$exp_dir} to view files" . PHP_EOL . PHP_EOL;
