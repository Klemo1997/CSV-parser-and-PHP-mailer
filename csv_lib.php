<?php
require ('PHPMailer/src/PHPMailer.php');
require ('PHPMailer/src/SMTP.php');
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/


class Record{

var $id;
var $name;
var $email;
var $login;
var $pass;

function __construct($id, $name, $email, $login)
{
$this->id = $id;
$this->name = $name;
$this->email = $email;
$this->login = $login;
$this->pass = $this->generatePassword();
}

function generatePassword($length = 15) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}


function get_array(){
$record[] = $this->id;
$record[] = $this->name;
$record[] = $this->email;
$record[] = $this->login;
$record[] = $this->pass;
return  $record;
}

}

class mailRecord{

var $id;
var $name;
var $email;
var $login;
var $pass;
var $publicIP;
var $privateIP;
var $ssh;
var $http;
var $https;
var $misc1;
var $misc2;

    function __construct($id, $name, $email, $login, $pass, $publicIP, $privateIP, $ssh, $http, $https, $misc1, $misc2)
    {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->login = $login;
    $this->pass = $pass;

    $this->publicIP = $publicIP;
    $this->privateIP = $privateIP;

    $this->ssh = $ssh;
    $this->http = $http;
    $this->https = $https;
    $this->misc1 = $misc1;
    $this->misc2 = $misc2;
    }

    function returnArray(){
        $retArray = array();
        $retArray[] = $this->id;
        $retArray[] = $this->name;
        $retArray[] = $this->email;
        $retArray[] = $this->login;
        $retArray[] = $this->pass;
        $retArray[] = $this->publicIP;
        $retArray[] = $this->privateIP;
        $retArray[] = $this->ssh;
        $retArray[] = $this->http;
        $retArray[] = $this->https;
        $retArray[] = $this->misc1;
        $retArray[] = $this->misc2;

        return $retArray;
    }



    function sendmail($mail,$ishtml,$vars,$body,$subject,$templateID){

        $array = $this->returnArray();



        for($i = 0 ; $i < count($vars); $i++){
            $body = str_replace($vars[$i],$array[$i],$body);
        }


        $mail->addAddress($this->email);     // Add a recipient
        $mail->Body    = $body;

        if(!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {

            require_once 'db_config.php';



            // Create connection
                        $conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DBNAME);
            //Set utf8 charset
                        $conn->set_charset("utf8");
            // Check connection
                        if($conn->connect_error){
                            die("Connection failed" . $conn->connect_error);
                        }


            $sql = "INSERT INTO mail_table".
                    " VALUES(DATE(NOW()),'$this->name','$subject','$templateID');     ";

            if ($conn->query($sql) === TRUE) {

            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();

        }
    }
}



function mailer_sendmails($sender, $pass, $textarea ,$delimiter,$htmlEnable,$senderName,$subject,$templateID){
$row = 1;

    //premenna pre sendera
    $textarea = str_replace("{{sender}}",$senderName,$textarea);

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.stuba.sk';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $sender;                 // SMTP username
    $mail->Password = $pass;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    $mail->CharSet = 'UTF-8';
    $mail->Subject  = $subject;
    //

    require_once 'csv_mailer_upload.php';
    if (isset($_FILES['attachmentFile']) &&  $_FILES['attachmentFile']['error'] == UPLOAD_ERR_OK){
        $mail->AddAttachment($_FILES['attachmentFile']['tmp_name'], $_FILES['attachmentFile']['name']);
        $error = ($_FILES["attachmentFile"]["size"] > 1048576);
        echo $_FILES["attachmentFile"]["size"];
    }
    else{
        $error = false;
    }

    echo $error;

    $mail->From = $sender;

    $mail->FromName = $senderName;

    if($htmlEnable == true)
        $mail->isHTML(true);                            // Set email format to HTML
    else
        $mail->isHTML(false);                            // Set email format to HTML





    if(!$error){
        if (($handle = fopen("input.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $num = count($data);

                if($row == 1){
                    foreach($data as $item){
                        $GLOBALS['vars'][] = '{{'.$item.'}}';
                    }
                }

                if($row != 1 && $num == 12){
                    $record = new mailRecord($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
                    $record->sendmail($mail,true,$GLOBALS['vars'],$textarea,$subject,$templateID);
                    $mail->clearAddresses();
                }
                $row++;
                if($num != 12){
                    throw new ErrorException("Wrong CSV input");
                    header('Location: csv_loader.php');
                }
            }
            fclose($handle);
        }
        echo "Emaily úspešne poslané";
    }
    else{
        echo "Email sa nedá poslať, príloha je väčšia ako 1mb";
    }

}



function parse_to_csv($records) {

    // open the "output" stream


$csv_stream = "id;meno;email;login;password \n";

for($i = 0; $i < count($records); $i++){
$record = $records[$i]->get_array();

$csv_stream.= $record[0].';'.$record[1].';'.$record[2].';'.$record[3].';'.$record[4]."\n";
}

$new_csv = fopen('CSVexport.csv', 'w');
fwrite($new_csv,$csv_stream);
fclose($new_csv);
}


