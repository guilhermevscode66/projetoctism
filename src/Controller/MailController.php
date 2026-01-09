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
    
    /**
     * Compose a simple HTML email template with CTA button (inline styles for email clients)
     */
    public function setTemplate(string $subject, string $title, string $greetingName, string $message, string $ctaText = '', string $ctaUrl = ''): void {
        $this->mail->Subject = $subject;
        $ctaHtml = '';
        if ($ctaText && $ctaUrl) {
            $ctaHtml = '<p style="margin-top:18px;"><a href="' . htmlspecialchars($ctaUrl, ENT_QUOTES, 'UTF-8') . '" style="display:inline-block;padding:10px 16px;background:#0d6efd;color:#ffffff;border-radius:6px;text-decoration:none;">' . htmlspecialchars($ctaText, ENT_QUOTES, 'UTF-8') . '</a></p>';
        }
        $this->mail->Body = '<!doctype html><html lang="pt-br"><head><meta charset="utf-8"></head><body style="font-family:Arial,Helvetica,sans-serif;color:#222;line-height:1.4;">'
            . '<div style="max-width:600px;margin:0 auto;padding:18px;">'
            . '<h2 style="color:#0d6efd;">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2>'
            . '<p>Olá ' . htmlspecialchars($greetingName, ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<div>' . $message . '</div>'
            . $ctaHtml
            . '<p style="margin-top:18px;color:#666;">Atenciosamente,<br/>Equipe do Sistema de Controle de Horários</p>'
            . '<hr style="border:none;border-top:1px solid #eee;">'
            . '<small style="color:#999;">Este email foi gerado automaticamente, não é necessário responder.</small>'
            . '</div></body></html>';
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
