<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();
$_SESSION["role"] = 'admin';


if ((isset($_SESSION["role"]) && $_SESSION["role"] !== "admin") || !isset($_SESSION["role"])) {
    header("Location: https://147.175.121.210:4132/zadanie/index.php");
}

//Ak sa nacitavalo existuju subory CSVexport a input, preistotu premazat

    $path="";
    $uploadFile = "input.csv";
    $downloadFile = "CSVexport.csv";

    if(file_exists($uploadFile))
    unlink($uploadFile);

    if(file_exists($downloadFile))
    unlink($downloadFile);



?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Nahraj CSV súbor</title>
</head>
<body>
    <div class="container">

       <div class="jumbotron" style="text-align: center; margin: 0 auto; margin-top: 3%">
           <form action="csv_upload.php" method="post" enctype="multipart/form-data">
               <h2>Vyber CSV súbor pre načítanie:</h2>
                <br><p>Prvá úloha, vygeneruje heslá a umožní stiahnutie.</p>

               <div class="custom-file" style="margin-bottom: 3%; max-width: 30%">
                   <label class="custom-file-label" for="CSVToUpload">Vyber CSV súbor</label>
                   <input type="file" class="custom-file-input" accept=".csv" name="CSVToUpload" id="CSVToUpload" required> <br>

               </div><br>
               Oddeľovač<br>
               <select name="delimiter" id="delimiter" class="custom-select" style="max-width: 10%; margin-bottom: 3%">
                   <option value=";">;</option>
                   <option value=",">,</option>
               </select> <br>
               <input type="submit" class="btn btn-primary" value="Nahraj CSV" name="submitCSV" id="submitCSV">

           </form>
           <br>

           <div class="jumbotron" style="text-align: center; margin: 0 auto; margin-top: -2%">
               <hr><br>
               <form action="csv_mailer_upload.php" method="post" enctype="multipart/form-data" style="margin: 0 auto; ">
                   <h2>Vyber CSV súbor pre načítanie:</h2>
                   <br><p>Druhá úloha, .</p>

                   <div class="custom-file" style="margin-bottom: 3%; max-width: 30%">
                       <label class="custom-file-label" for="CSVToUpload">Vyber CSV súbor</label>
                       <input type="file" class="custom-file-input" name="CSVToUpload1" id="CSVToUpload1"> <br>
                   </div><br>
                   Oddeľovač<br>
                   <select name="delimiter1" id="delimiter1" class="custom-select" style="max-width: 10%; margin-bottom: 3%">
                       <option value=";">;</option>
                       <option value=",">,</option>
                   </select> <br>

                   <input type="submit" class="btn btn-primary" value="Nahraj CSV" name="submitCSV1" id="submitCSV1">

               </form>
               <br><hr><div>        <a class="btn btn-primary" href="mails_table.php"  >Tabuľka rozposlaných mailov</a></div>
           </div>


       </div>


    </div>




</body>
</html>
