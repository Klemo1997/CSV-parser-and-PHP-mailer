<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$file = basename($_GET['file']);


if($file == "CSVexport.csv"){
    if(!file_exists($file)){
        die('file not found');
    } else {
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file");
        header("Content-Type: application/csv");
        header("Content-Transfer-Encoding: binary");

        // read the file from disk
        readfile($file);
    }

}
else{echo "Error";}
?>

