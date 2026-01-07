<?php
namespace Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class MailController {
    public $mail;
    protected $errorInfo = '';

    public function __construct() {
        $this->mail = new PHPMailer();
        // Configurações SMTP comuns aqui
        $this->mail->isSMTP();
        // Em produção mantenha debug desligado para não bloquear/responder no output
        $this->mail->SMTPDebug = 0;
        $this->mail->CharSet = 'utf-8';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'guilhermekbaccin@gmail.com';
        $this->mail->Password = 'unvbnuxfkhriweyo'; // cuidado com senhas hardcoded
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
        // reduzir tempo de timeout para evitar que requisições web fiquem presas aguardando SMTP
        $this->mail->Timeout = 10; // segundos
        $this->mail->SMTPAutoTLS = false;
        $this->mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
        
        $this->mail->setFrom('guilhermekbaccin@gmail.com', 'Guilherme Baccin');
        $this->mail->isHTML(true);
        $this->mail->Subject = '';
        $this->mail->Body = '';
    }
    
    public function send() {
        try {

            if (!$this->mail->send()) {
                $this->errorInfo = $this->mail->ErrorInfo;
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->errorInfo = $e->getMessage();
            return false;
        }
    }

    public function getErrorInfo() {
        return $this->errorInfo;
    }
}
