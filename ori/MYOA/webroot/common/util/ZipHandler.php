<?PHP  

/**
 * 附件打包操作类
 * 
 * @author      Tsao
 * @copyright   Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license     http://framework.zend.com/license/   BSD License
 * @version     Release: @package_version@
 * @link         http://framework.zend.com/package/PackageName
 * @since        Class available since Release 1.5.0
 * @deprecated  Class deprecated in Release 2.0.0
 */

Class PHPZip
{
    //压缩ZIP文件
    Function Tozip($file,$zipname)
    {
        $zip=new ZipArchive();
        $filenameZip = CMS_ROOT.'/wisdom_party/downfile/'.$zipname.'.zip';
        if (!file_exists($filenameZip)){
            $zip->open($filenameZip,ZipArchive::OVERWRITE);//创建一个空的zip文件
            
            Foreach($file as $key => $filename)
            {
                IF(is_file($filename))
                {
                    $zip->addFile($filename,$key);   
                }  
            }
            $zip->close();
        }
        $this->DownLoadZip($filenameZip,  basename($filenameZip));
        
        @unlink($filenameZip); //下载完成后要进行删除
    }
    
    //下载服务器上打包好的ZIP文件
    Function DownLoadZip($filepath,$filename)
    {
        if (file_exists($filepath))
        {
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', TIME_NOW) . 'GMT');
            header('Cache-control: max-age=31536000');
            header('Expires: ' . gmdate('D, d M Y H:i:s', TIME_NOW + 31536000) . 'GMT');
            header('Content-Disposition: attachment; filename=' . $filename);
            $extension = strtolower(substr(strrchr($filename, '.'), 1));

            if ($extension == 'gif')
                header('Content-type: image/gif');
            else if ($extension == 'jpg' or $extension == 'jpeg')
                header('Content-type: image/jpeg');
            else if ($extension == 'png')
                header('Content-type: image/png');
            else if ($extension == 'pdf')
                header('Content-type: application/pdf');
            else
                header('Content-type: unknown/unknown');
            ob_clean();
            flush();
            $fp = fopen($filepath, 'rb');
            while (!feof($fp)) {
                echo fread($fp, 2048);
            }
            fclose($fp);
       }else {
           echo "<script>alert('对不起,您要下载的文件不存在');window.close();</script>";
       }
       
    }
        
    //函数用途:压缩文件,可以是单个也可以是数组的多个  
    //参数详解:$file需要压缩的文件(可为数组),$zipfilename压缩后的文件名及存放路径,$Todo处理方式1提供下载2保存文件在服务器  
    Function ZipFile($file)
    {
        $zipname = date('YmdHis',time());
        IF(is_array($file))
        {  
            $this->Tozip($file, $zipname);  
        } 
    }  
}  