<?php
include 'csv_lib.php';

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
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

    <script src="https://cloud.tinymce.com/5/tinymce.min.js?apiKey=zedok0y6t26hmm59z5hq8b6nuug9m31bqnyw336xxundn1ed"></script>
    <script src="text_editor.js"></script>

    <title>Rozposielanie mailov</title>
</head>
<body>
<div class="container">

    <div class="jumbotron" align="center">
        <div class="jumbotron" style="background-color: #544e52; color: white; text-align: center; margin-top: -5%"  ><h2>Odoslať maily</h2></div>
        <div>        <a class="btn btn-primary" href="csv_loader.php" style="width: 10%;" id="returnButton">Späť</a></div>
        <?php
                if(isset($_POST['submitCSV1'])){
                    $error = false;
                    $FileName = "input";
                    $FileType = strtolower(pathinfo($_FILES["CSVToUpload1"]["name"],PATHINFO_EXTENSION));

                    if($FileType != 'csv')
                    {
                        echo "Súbor nieje typu .csv !";
                        $error = true;
                        //redirect back on loader
                    }

                    // Check file size
                    if ($_FILES["CSVToUpload1"]["size"] > 1048576 )
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
                        if (move_uploaded_file($_FILES["CSVToUpload1"]["tmp_name"], $FileName.".".$FileType))
                        {
                            echo "<p>Údaje úspešne načítané.</p> <br>";
                        }
                        else
                        {
                            echo "Error, CSV sa nenačítalo.";
                            //redirect back on loader
                        }
                    }





                    $row = 1;
                    $columns = 0;



                    if (($handle = fopen("input.csv", "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, $_POST['delimiter1'])) !== FALSE) {
                            if($row == 1){
                                echo "Zoznam premenných: <br>";
                                foreach($data as $item){
                                    echo ' {{'.$item.'}} ';
                                }

                            }
                            $columns = count($data);
                            $row++;
                        }
                        fclose($handle);
                    }

                    if($columns != 12){
                        throw new ErrorException("Wrong CSV input");
                        header('Location: csv_loader.php');
                    }
                }


                if( isset($_POST['sendMails']) ){
                    //delimiter ma ;, pretoze ho ukklada tento s ubor

                    //senderName, subject
                    mailer_sendmails($_POST['InputEmail'],$_POST['InputPassword'],$_POST['full-featured'],';',$_POST['htmlEnable'],$_POST['senderName'],$_POST['subject'],$_POST['template']);

                }
        ?>





        <form action="#" method="post" enctype="multipart/form-data">


            <div class="form-group" style="width: 50%;text-align: center" align="center">


            </div>

            <div class="row" style="width: 70%">
                <div class="col">
                    <label for="subject">Predmet správy</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Zadaj predmet správy" value="Predmet - WEBTE2" required>
                </div>
                <div class="col">
                    <label for="template">Šablóna</label>
                    <select name="template" id="template" class="custom-select"  onchange="loadTemplate()">
                        <option value="1">sablona1</option>
                        <option value="2">sablona2-bez html</option>
                    </select> <br>
                </div>
            </div>



            <label>Príloha</label><br>
            <div class="custom-file" style="margin-bottom: 3%; max-width: 30%">

                <label class="custom-file-label" for="attachmentFile">Vyber súbor</label>
                <input type="file" class="custom-file-input" name="attachmentFile" id="attachmentFile"> <br>




            </div><br>



                <textarea id="full-featured" name="full-featured"></textarea>


            <div class="form-check" >
                <input class="form-check-input" type="radio" name="htmlEnable" id="disable" value="false" checked>
                <label class="form-check-label" for="exampleRadios2">
                    Povoliť HTML
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="htmlEnable" id="enable" value="true">
                <label class="form-check-label" for="exampleRadios1">
                    Nepovoliť HTML
                </label>
            </div>

            <br>

            <div class="form-group" style="width: 50%;text-align: center" align="center">
                <label for="senderName">Odosielateľ</label>
                <input type="text" class="form-control" id="senderName" name="senderName" placeholder="Zadaj meno odosielateľa" value="Webmaster" required>

            </div>

            <div class="row" style="width: 70%">
                <div class="col">
                        <label for="InputEmail">AIS mail</label>
                        <input type="email" class="form-control is-invalid" id="InputEmail" name="InputEmail" aria-describedby="emailHelp" placeholder="Zadaj AIS mail" required>
                        <div class="invalid-feedback">
                            Pre pokračovanie zadaj AIS mail !
                        </div>
                </div>
                <div class="col">
                        <label for="InputPassword">AIS heslo</label>
                        <input type="password" class="form-control is-invalid" id="InputPassword" name="InputPassword" placeholder="Zadaj AIS heslo" required>
                        <div class="invalid-feedback">
                            Pre pokračovanie zadaj AIS heslo !
                        </div>
                </div>
            </div>




            <br><input type="submit" class="btn btn-primary" value="Odoslať" name="sendMails" id="sendMails" onsubmit="getHtmlToTextArea()" >
        </form>
    </div>
    <script>
        function getHtmlToTextArea(){
            alert( console.debug(tinyMCE.activeEditor.getContent()));
        }



        var html1 = '<h2>Dobrý deň {{meno}}, </h2><br>' +
            'Na predmete Webové technológie 2 budete mať k dispozícii vlastný virtuálny linux server, ktorý budete\n' +
            'používať počas semestra, a na ktorom budete vypracovávať zadania.<br> Prihlasovacie údaje k Vašemu serveru\n' +
            'su uvedené nižšie.\n' +
            '<br>ip adresa: {{publicIP}}\n' +
            '<br>prihlasovacie meno: {{login}}\n' +
            '<br>heslo: {{password}}\n' +
            '<br>Vaše web stránky budú dostupné na: http:// {{publicIP}}:{{http}}\n' +
            '<br><br>S pozdravom,\n' +
            '{{sender}}';

        var html2 = 'Dobrý deň {{meno}}, \n' +
            'Na predmete Webové technológie 2 budete mať k dispozícii vlastný virtuálny linux server, ktorý budete\n' +
            'používať počas semestra, a na ktorom budete vypracovávať zadania.\n' +
            'Prihlasovacie údaje k Vašemu serveru\n' +
            'su uvedené nižšie.\n' +
            'ip adresa: {{publicIP}}\n' +
            'prihlasovacie meno: {{login}}\n' +
            'heslo: {{password}}\n' +
            'Vaše web stránky budú dostupné na: http:// {{publicIP}}:{{http}}\n' +
            'S pozdravom,\n' +
            '{{sender}}';



        $('#full-featured').val(html1);

        function loadTemplate(){
            var activeEditor = tinyMCE.get('full-featured');
            var content = 'HTML or plain text content here...';
            if(activeEditor!==null){
                if($('#template').val() == 1)
                    activeEditor.setContent(html1);
                if($('#template').val() == 2)
                    activeEditor.setContent(html2);
            } else {
                $('#full-featured').val(html2);
            }







        }



    </script>

</div>
</body>
</html>
