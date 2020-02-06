<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require $_SERVER['DOCUMENT_ROOT'] . '/libs/phpmailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/libs/phpmailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/libs/phpmailer/src/SMTP.php';

class Mailer
{
    private $mail;

    public function __construct()
    {
        $smtpConfig = Poker_Variables::getList([
            'smtp_host',
            'smtp_secure',
            'smtp_port',
            'smtp_username',
            'smtp_password'
        ]);
        $this->mail = new PHPMailer();

        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            )
        );

        $this->mail->Host = $smtpConfig['smtp_host']['value'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $smtpConfig['smtp_username']['value'];
        $this->mail->Password = $smtpConfig['smtp_password']['value'];
        $this->mail->SMTPSecure = $smtpConfig['smtp_secure']['value'];
        $this->mail->Port = $smtpConfig['smtp_port']['value'];

        $this->mail->setFrom($smtpConfig['smtp_username']['value'], 'Poker Cam');
    }

    /**
     * return true if success, otherwise return error
     *
     * @param $to
     * @param $subject
     * @param $content
     * @return bool|string
     */
    public function send($to, $subject, $content)
    {
        $this->mail->addAddress($to);
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $content;
        $this->mail->AltBody = strip_tags($content);

        try {
            $this->mail->send();
            return true;
        } catch (Exception $exception) {
            return $this->mail->ErrorInfo;
        }
    }
}