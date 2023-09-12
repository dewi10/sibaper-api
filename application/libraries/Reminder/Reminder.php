<?php
// Import PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Require necessary files
require APPPATH . 'third_party/PHPMailer/src/Exception.php';
require APPPATH . 'third_party/PHPMailer/src/PHPMailer.php';
require APPPATH . 'third_party/PHPMailer/src/SMTP.php';  
require_once 'controllers/Api_reminder.php';



class Api_email extends Api
{
    public function __construct()
    {
        parent::__construct();
    }


    public function send_reminder()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        // header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");

				// $envFile = __DIR__ . '/../../.env';
				// $envVars = parse_ini_file($envFile);

        // Get email data from Vuetify
				$reminder = new getReminder();
				$data = $reminder->getData();
				

        $to_email = 'dewisept.intimap@gmail.com';
				$cc_email = 'dewi.wataru18@gmail.com';
        $subject = 'Reminder_'.$data['subject']; 
				$virtual_account = $data['virtual_account'];
				$name = $data['name'];
				$email = $data['email'];
				$no_telephone = $data['no_telephone'];
				$date = $data['date']; 
				$keterangan = $data['keterangan']; 
			 
				$message = "<div style='text-align:left;background:#f7f7f7;width:80%;margin:auto 0;padding:30px;color:#000;font-size:14px;border:3px solid #ddd;'>
        <b style='font-size:18px;margin-bottom:5px;text-transform:uppercase;'>REMINDER FOR CUSTOMER : $name ! </b><br><br>

       
						<b><a style='color:#e51010;'>
						Reminder Date :  $date <br><br>
						</a></b>
						<a style='color:#000;'>

						Virtual Account :  $virtual_account <br>
            Nama Customer :  $name <br>
            Email :  $email <br>
						Phone :  $no_telephone <br> 
            Keterangan: $keterangan <br><br>
        </a>
 
				 <div style='clear:both'></div>
            <br> <br>
            E-mail ini otomatis, harap tidak membalas pesan<br><br/><br>
            Best Regard, <br>
            <b>E-Admin</b>
        </div>";

       
        // Configure PHPMailer
        $mail = new PHPMailer;
        $mail->SMTPDebug = 1;  // Enable verbose debug output
        $mail->isSMTP();       // Send using SMTP
        $mail->Host = 'mail.street-directory.com.au';                    // Set the SMTP server to send through
        $mail->SMTPAuth = true;   
        $mail->SMTPSecure = 'ssl'; 
        $mail->Username = 'phpmailer@intellitrac.co.id';  // Replace with your Gmail address
        $mail->Password = '1nt1m4pPHPmailer';  // Replace with your Gmail password
        $mail->Port = 465;
        $mail->From="no-reply@intimap.co.id";
        $mail->AddAddress($to_email);
    		$mail->AddCC($cc_email); // Add CC recipients
				$mail->FromName="NO REPLY";
				$mail->AddReplyTo("no-reply@intimap.co.id","NO REPLY");
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message; 
 

        try {
					// Send the email
					$mail->send();
					$response = array('status' => 'success', 'message' => 'Email sent successfully');
					return $response;
			} catch (Exception $e) {
					// Handle errors
					$response = array('status' => 'error', 'message' => 'Error sending email: ' . $mail->ErrorInfo);
					return $response;
			}

        header('Content-Type: application/json');
        echo json_encode($response);
    }

		public function schedule_email()
{
    // Get email data from Vuetify or set the data programmatically
    $data = array(
        'to_email' => 'recipient@example.com',
        'subject' => 'subject',
        'virtual_account' => 'virtual_account',
        'name' => 'name',
        'email' => 'email',
        'no_telephone' => 'no_telephone',
        'date' => date('Y-m-d H:i:s'),
        'keterangan' => 'Reminder message'
    );

    // Call the send_reminder() function with the email data
    $this->send_reminder($data);
}


}
