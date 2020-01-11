<?php

$UsedLogFile = "NetbeansSVN.log";
$pwd = __DIR__;
$originPath = str_replace('/', DIRECTORY_SEPARATOR, '/Users/JessieicLu/Documents/JProject/wap-www-jjwxc-net/');
$timeStmp = date("Y-m-d-h_i_s", time());
$rootPath = str_replace('/', DIRECTORY_SEPARATOR, '/Users/JessieicLu/Desktop/') . $timeStmp . DIRECTORY_SEPARATOR;
$LogPath = $pwd . DIRECTORY_SEPARATOR . $UsedLogFile;

function deldir($dir) {
    //先删除目录下的文件：
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }

    closedir($dh);
    //删除当前文件夹：
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}

ini_set('safe_mode', FALSE);
if (is_dir($rootPath)) {
    deldir($rootPath);
}
mkdir($rootPath, 0777);
$cmd = "sudo chmod  -R 0777  " . $rootPath;
shell_exec($cmd);

if (file_exists($LogPath)) {
    $str = file_get_contents($LogPath);
    //正则去掉中文空格
    $patten = "/[\s|　]+/"; //注意|后面的跟的是全角空格
    $dealstr = preg_replace($patten, '&&&&', $str);
    $contentArr = explode("&&&&", $dealstr);
} else {
    echo "not fount file";
}

if (!empty($contentArr)) {
    foreach ($contentArr as $filepath) {
        $cpFilePath = $originPath . $filepath;
        if (file_exists($cpFilePath) && is_file($cpFilePath)) {
            $noworiginPathArr = explode(DIRECTORY_SEPARATOR, $filepath);
            array_pop($noworiginPathArr);
            $noworiginPath = implode(DIRECTORY_SEPARATOR, $noworiginPathArr);
            $needFilePath = $rootPath . $noworiginPath;
            if (!file_exists($needFilePath)) {
                mkdir($needFilePath, 0777, TRUE);
            }
            copy($cpFilePath, $rootPath . $filepath);
        }
    }
}
$cmd = "sudo chmod  -R 0777  " . $rootPath;
shell_exec($cmd);
?>