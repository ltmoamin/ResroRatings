<?php
	if( $_POST ){
		$form_email = ( isset( $_POST['from_email'] ) ) ? $_POST['from_email'] : '';				//'contact@enroutedigitallab.com';
		
		$to	  = ( isset( $_POST['recipient_email'] ) ) ? $_POST['recipient_email'] : '';
		$name    = ( isset( $_POST['name'] ) ) ? $_POST['name'] : '';
		$email   = ( isset( $_POST['email'] ) ) ? $_POST['email'] : '';				$website   = ( isset( $_POST['website'] ) ) ? $_POST['website'] : '';
		$message = ( isset( $_POST['message'] ) ) ? $_POST['message'] : '';

		$subject = "New email notificatiion";		
		$output  = "";
		$output .= sprintf("<strong>Sender Name: </strong> %s <br />", $name );
		$output .= sprintf("<strong>Sender Email: </strong> %s <br />", $email );				$output .= sprintf("<strong>Sender Website: </strong> %s <br />", $website );
		$output .= sprintf("<strong>Message: </strong> %s <br />", $message );
			
		$headers  = "From: Contact Form < " . $form_email . " >\n";
		$headers .= "Cc: Contact Form < " . $form_email . " >\n"; 
		$headers .= "X-Sender: Contact Form < " . $form_email ." >\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: " . $form_email . "\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
			
		$retval = mail( $to, $subject, $output, $headers );
		
		$send_message = '';
		if( $retval == true ) {			
			$send_message .= '<div class="alert alert-success">';
			$send_message .= '<strong>Success!</strong> Message has been sent successfully';
			$send_message .= '</div>';
		} else {			
			$send_message .= '<div class="alert alert-danger">';
			$send_message .= '<strong>Error!</strong> Message could not be sent. Try again with correct information.';
			$send_message .= '</div>';
		}
	}
?>

<div class="contact-submit-message">
	<?php echo $send_message;  ?>
</div>