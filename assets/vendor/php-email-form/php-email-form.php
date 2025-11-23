<?php
/**
 * PHP Email Form Class - Version 1.0
 * URL: https://bootstrapmade.com/php-email-form/
 * Author: BootstrapMade.com
 * License: https://bootstrapmade.com/license/
 */
class PHP_Email_Form {
    public $to = '';
    public $from_name = '';
    public $from_email = '';
    public $subject = '';
    public $smtp = [];
    public $ajax = true;
    public $message = [];
    public $smtp_debug = 0;
    
    public function add_message($content, $label = '') {
        if ($label) {
            $this->message[] = "<p><strong>$label:</strong> $content</p>";
        } else {
            $this->message[] = "<p>$content</p>";
        }
    }
    
    public function send() {
        $to = $this->to;
        $subject = $this->subject;
        $from_name = $this->from_name;
        $from_email = $this->from_email;
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $from_name <$from_email>\r\n";
        $headers .= "Reply-To: $from_email\r\n";
        
        $message = implode("\n", $this->message);
        
        if (!empty($this->smtp)) {
            return $this->smtp_send($to, $subject, $message, $headers);
        } else {
            return mail($to, $subject, $message, $headers);
        }
    }
    
    private function smtp_send($to, $subject, $message, $headers) {
        require 'PHPMailer/PHPMailerAutoload.php';
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = $this->smtp_debug;
        $mail->Host = $this->smtp['host'];
        $mail->Port = $this->smtp['port'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->smtp['username'];
        $mail->Password = $this->smtp['password'];
        $mail->SMTPSecure = $this->smtp['encryption'];
        
        $mail->setFrom($this->from_email, $this->from_name);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->msgHTML($message);
        $mail->AltBody = strip_tags($message);
        
        if(!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}
?>
