<?php
/**
 * Created by PhpStorm.
 * User: matus
 * Date: 19-May-19
 * Time: 17:36
 */
require_once 'db_config.php';
// Create connection
$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DBNAME);
//Set utf8 charset
$conn->set_charset("utf8");
// Check connection
if($conn->connect_error){
    die("Connection failed" . $conn->connect_error);
}


$sql = "SELECT sent, student_name, subject, template_id FROM mail_table";
$result = $conn->query($sql);

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


    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" crossorigin="anonymous">

    <title>História rozposlaných mailov</title>
</head>
<body>
    <div class="container">

        <div class="jumbotron" style="width: 100% ;  margin: 0 auto;">

            <header align="center"><h2>História odoslaných mailov</h2><hr><br></header>
            <div style="width: 70%; margin: 0 auto;" align="center">
                <div style="margin-bottom: 5%;width: 40%">        <a class="btn btn-primary" href="csv_loader.php" style="width: 100%;" id="returnButton">Späť</a></div>
                <table id='mailsTable' class='display' style='width: 100%; margin: 0 auto; background-color: white'>
                    <thead>
                    <th>Dátum odoslania</th>
                    <th>Komu</th>
                    <th>Predmet správy</th>
                    <th>Číslo šablóny</th>
                    </thead>
                    <tbody>
                    <?php

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . $row['sent']. "</td><td>" . $row["student_name"]. "</td><td> " . $row["subject"]. "</td><td>".$row["template_id"]."</td></tr>";
                        }
                    } else {
                        echo "Error: 0 results";
                    }

                    $conn->close();

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready( function () {
        $('#mailsTable').DataTable({
            "paging":    true,
            "info":      true,
            "searching": true ,
            "columns":[
                {"sortable": true},
                {"sortable": true},
                {"sortable": true},
                {"sortable": true}
            ]
        });
    });
</script>
</html>
