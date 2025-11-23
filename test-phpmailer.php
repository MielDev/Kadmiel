<?php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure l'autoloader de Composer
require __DIR__ . '/vendor/autoload.php';

// Créer une nouvelle instance de PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'moignon168@gmail.com';
    $mail->Password = 'lrqg qjgr elrp fjaf';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    // Désactiver la vérification SSL (uniquement en développement)
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    // Destinataires
    $mail->setFrom('moignon168@gmail.com', 'Test PHPMailer');
    $mail->addAddress('kadmieltognon5@gmail.com');

    // Contenu de l'email
    $mail->isHTML(true);
    $mail->Subject = 'Test PHPMailer';
    $mail->Body = '<h1>Test réussi !</h1><p>Ceci est un test d\'envoi d\'email avec PHPMailer.</p>';
    $mail->AltBody = 'Test réussi ! Ceci est un test d\'envoi d\'email avec PHPMailer.';

    // Envoyer l'email
    $mail->send();
    echo 'Message envoyé avec succès';
    
} catch (Exception $e) {
    echo "Le message n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
}
