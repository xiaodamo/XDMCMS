<?php
class Imageupload extends CI_Controller
{
    public $targetDir ;
    public $uploadDir ;
    public $default_folder = 'images';

    /**
     * 构造函数
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
        // Support CORS
        // header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }
        
        
        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }
        
        // header("HTTP/1.0 500 Internal Server Error");
        // exit;
        
        
        // 5 minutes execution time
        @set_time_limit(10 * 60);
        
        // Uncomment this one to fake upload time
        usleep(5000);
        
        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
        
        // Get a file name
        if (isset($_REQUEST["name"])) {
        	$oldfileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $oldfileName = $_FILES["file"]["name"];
        } else {
            $oldfileName = uniqid("file_");
        }

        $sExtension = substr($oldfileName, (strrpos($oldfileName, '.') + 1));//找到扩展名
        $sExtension = strtolower($sExtension);
        $fileName = getMicrosecond().".".$sExtension;

        while(file_exists($this->uploadDir.'/'.$fileName)){
            $temp=explode(".",$fileName);//分割字符串
            $fileName = $temp[0]."0".".".$temp[1];//主文件名后面加0
        }
        $fid = !empty($_REQUEST["id"])?$_REQUEST["id"]:0;


        /*
         * 上传图片默认路径
         */
        $folder = '..'.UPLOADS.$this->default_folder;
        $this->targetDir = $folder.'_tmp';
        $this->uploadDir = $folder;

        // Create target dir
        if (!file_exists($this->targetDir)) {
            mkdirsByPath($this->targetDir);
        }

        // Create target dir
        if (!file_exists($this->uploadDir)) {
            mkdirsByPath($this->uploadDir);
        }
        
        $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $md5File = $md5File ? $md5File : array();
        
        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
            die('{"jsonrpc" : "2.0", "result" : null, "id" : "'.$fid.'", "exist": 1}');
        }
        
        $filePath = $this->targetDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadPath = $this->uploadDir . DIRECTORY_SEPARATOR . $fileName;
        
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        
        
        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($this->targetDir) || !$dir = opendir($this->targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "'.$fid.'"}');
            }
        
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
        
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
        
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }
        
        
        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "'.$fid.'"}');
        }
        
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "'.$fid.'"}');
            }
        
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "'.$fid.'"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "'.$fid.'"}');
            }
        }
        
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        
        @fclose($out);
        @fclose($in);
        
        @rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        
        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "'.$fid.'"}');
            }
        
            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }
        
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
        
                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }
        
                flock($out, LOCK_UN);
            }
            @fclose($out);
        }

        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "'.$fid.'", "name" : "'.$this->default_folder."/".$fileName.'"}');
    }


}