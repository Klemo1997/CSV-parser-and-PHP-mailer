<?php
include 'csv_lib.php';

/*skus uploadnut na server a potom precitat... po skonceni zas vymazat*/
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <title>Stiahni CSV</title>
</head>


<style>
    #returnButton{
        position: absolute;
        top: 7%;
        right: 15%;
    }
</style>


<body>
    <div class="container" style="height: 100%">
        <div class="jumbotron" style="text-align: center ; margin-top: 3%">
        <div>        <a class="btn btn-primary" href="csv_loader.php" style="width: 10%" id="returnButton">Späť</a></div>

            <h2 style="margin-bottom: 5%">Stiahni upravený CSV súbor: </h2>
    <?php

        $error = false;
        $FileName = "input";
        $FileType = strtolower(pathinfo($_FILES["CSVToUpload"]["name"],PATHINFO_EXTENSION));


        if($FileType != 'csv')
        {
            echo "Súbor nieje typu .csv !";
            $error = true;
            //redirect back on loader
        }

        // Check file size
        if ($_FILES["CSVToUpload"]["size"] > 1048576 )
        {
            $error = true;
            echo "Tvoj súbor je väčší ako 1mb.";
            //redirect back on loader
        }

        // Check if $uploadOk is set to 0 by an error
        if ($error) {
            echo "Error, CSV sa nenačítalo.";
            //redirect back on loader

        }
        else {
            if (move_uploaded_file($_FILES["CSVToUpload"]["tmp_name"], $FileName.".".$FileType))
            {
                echo "<p>Údaje úspešne načítané, súbor je pripravený na stiahnutie.</p> <br>";
            }
            else
            {
                echo "Error, CSV sa nenačítalo.";
                //redirect back on loader
            }
        }


        //Iterates CSV and parses to record object


        $row = 1;
        $columns = 0;
        if (($handle = fopen("input.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $_POST['delimiter'])) !== FALSE) {
                $columns = count($data);
            }
            fclose($handle);
        }


        if($columns != 4){
            throw new ErrorException("Wrong CSV input");
            header('Location: csv_loader.php');
        }

        if (($handle = fopen("input.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $_POST['delimiter'])) !== FALSE) {

                if($columns == 4){
                    $num = count($data);
                    //if num = pocet stlpcov error
                    if($row != 1 && $num == $columns){
                        $records[] = new Record($data[0],$data[1],$data[2],$data[3]);
                    }
                    $row++;


                    if($num != $columns){
                        throw new ErrorException("Wrong CSV input");
                        header('Location: csv_loader.php');
                    }

                }
            }

                 //save the csv on server
                 parse_to_csv($records);


            fclose($handle);
        }

        echo "<br> Súbor obsahuje celkovo ". count($records) ;
        if(count($records) == 1) echo " záznam.";

        elseif(count($records) == 2 || count($records) == 3 || count($records) == 4) echo " záznamy.";

        else echo " záznamov.";

        ?>

      <br><br>  Stiahnuť vygenerovaný CSV súbor možeš tu : <br><br>
        <a target="_blank" class="btn btn-primary"  rel="noopener noreferrer" href="csv_download.php?file=CSVexport.csv" style="width: 40%" >Stiahni</a>

      <br>

        </div>
    </div>
</body>

<script>

</script>
</html>





