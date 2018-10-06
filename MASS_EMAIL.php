<?php

//PHP Mass Email Messaging with pdf file as attachment and read email list stored in MYSQL Database 

//Conributor: Harcharan Jit Singh
//Designation: Senior System Analyst Cum Programmer
//Centre of Information and Technology Management
//Thapar Institute Of Engineering and Technology, Patiala, Punjab

require_once "Mail.php";
 
//MYSQL Database Connection

$servername = "localhost"; // Replace with your server name
$username = "MYSQL_User_Name"; // Replace with MYSQL username
$password = "MYSQL_User_Password"; // Replace with MYSQL password
$dbname = "MYSQL_Database"; // Replace with MYSQL DB

//Create a table under this database with coloumns id as number, name as varchar, emailadd as varchar, status as text

//CREATE TABLE userdata ( 
//    id int NOT NULL,
//    name varchar(75) NOT NULL,
//    emailadd varchar(75) NOT NULL,
//    status varchar(1),
//    CONSTRAINT user_id PRIMARY KEY (id)
// );


// Create Database Connection 
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check Connection 
if ($conn->connect_error) 
	{
    	die("Connection failed: " . $conn->connect_error);
	} 
//Select Email user list 
$sql = "select userid,name,emailadd,status from userdata where status='N'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
  	{
    	// output data of each row
    	while($row = mysqli_fetch_assoc($result)) 
		{
  		//      echo "id: " . $row["userid"]. " - Name: " . $row["name"]. " " . $row["emailadd"]. "<br>";
    			//email 




//Sender Email credentials
	$host = "smtp.company.com"; // Replace with smtp.gmail.com
	$port = 587;  // Replace with your SMTP PORT
	$username = "email@comapany.domain"; //Replace with abc@gmail.com
	$password = "Email_User_Password"; // Replace with Email Password


	$from = "Director Company director_company@company.domain>"; //Replace with abc@gmail.com

	$to = $row["emailadd"];

//Attachment file
$filepath1="INVITE.pdf";

//Email Subject
$subject = "Invitation "; 

//Body text of Email. Can either be text or HTML
$bodytext = "Dear " . $row["name"] .  ",<br>

	<p> Please find attached the invitation for an event </p>
<p>
Look forward to meeting you and your spouse.
</p>

<br><br>
Sincerely
<br>
Director
"; 

//Body text of Email. Can either be text or HTML
$body = "If you can see this MIME than your client doesn't accept MIME types!\r\n"
     ."--1a2a3a\r\n";

$file = file_get_contents("INVITE.pdf");

 
//Concatenate body text/html strings
$body .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
  ."Content-Transfer-Encoding: 7bit\r\n\r\n"
   . $bodytext . "\r\n"
   ."--1a2a3a\r\n";

  
$body .= "Content-Type: application/pdf; name=\"INVITE.pdf\"\r\n"
  ."Content-Transfer-Encoding: base64\r\n"
  ."Content-disposition: attachment; file=\"INVITE.pdf\"\r\n"
  ."\r\n"
  .chunk_split(base64_encode($file))
  ."--1a2a3a--";

//MIME
$mime1= "1.0";
$content_t="multipart/mixed; boundary=\"1a2a3a\"";
$headers = array ('From' => $from,
'To' => $to,
'Subject' => $subject,
'MIME-Version' => $mime1,
'Content-type' => $content_t);
$smtp = Mail::factory('smtp',
array ('host' => $host,
'port' => $port, 
'auth' => true,
'username' => $username,
'password' => $password));
//$headers .= "MIME-Version: 1.0" . "\r\n";
//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
          echo("<p> Email Not Sent id: " . $row["userid"] . " Message: " . $mail->getMessage() . "</p>"); 
} 
else {
          echo("<p>Message successfully sent! id: " . $row["userid"] . " Name: " . $row["name"] . " with  email address " . $row["emailadd"] . "</p>");
}        
      // Update Status Field in table as Email Sent Sucsessfully
       $updateq = "update userdata set status='Y' where userid=" . $row["userid"];
        mysqli_query($conn,$updateq);
	}
    } 
else 
{
    echo "0 results";
}
$conn->close();


?>





