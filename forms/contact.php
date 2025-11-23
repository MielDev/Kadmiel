<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fonction pour envoyer une réponse JSON
function sendJsonResponse($status, $message, $data = []) {
    header('Content-Type: application/json');
    $response = [
        'status' => $status,
        'message' => $message
    ];
    if (!empty($data)) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit();
}

// Définir les en-têtes CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Gérer la requête OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Vérifier si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    sendJsonResponse('error', 'Méthode non autorisée');
}

// Inclure PHPMailer
require __DIR__ . '/../vendor/autoload.php';

// Récupérer les données du formulaire
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'Nouveau message du formulaire de contact';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validation des champs obligatoires
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    sendJsonResponse('error', 'Tous les champs obligatoires doivent être remplis');
}

// Validation de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    sendJsonResponse('error', 'L\'adresse email n\'est pas valide');
}

try {
    // Créer une nouvelle instance de PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'moignon168@gmail.com';
    $mail->Password = 'lrqg qjgr elrp fjaf';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    // Désactiver la vérification SSL (à ne pas faire en production)
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    // Destinataires
    $mail->setFrom('moignon168@gmail.com', $name);
    $mail->addAddress('kadmieltognon5@gmail.com');
    $mail->addReplyTo($email, $name);

    // Charger le template HTML
    $template = file_get_contents(__DIR__ . '/../email-templates/contact-email.html');
    
    // Remplacer les variables dans le template
    $replace = [
        '{{name}}' => htmlspecialchars($name),
        '{{email}}' => htmlspecialchars($email),
        '{{subject}}' => htmlspecialchars($subject),
        '{{message}}' => nl2br(htmlspecialchars($message)),
        '{{date}}' => date('d/m/Y à H:i')
    ];
    
    $htmlContent = str_replace(
        array_keys($replace),
        array_values($replace),
        $template
    );
    
    // Contenu de l'email
    $mail->isHTML(true);
    $mail->Subject = 'Nouveau message de contact : ' . $subject;
    $mail->Body = $htmlContent;
    
    // Version texte brut pour les clients mail qui ne supportent pas le HTML
    $mail->AltBody = "Nouveau message de contact\n\n" .
        "Nom: {$name}\n" .
        "Email: {$email}\n" .
        "Sujet: {$subject}\n" .
        "Date: " . date('d/m/Y à H:i') . "\n\n" .
        "Message:\n" . $message;

    // Envoyer l'email
    $mail->send();
    sendJsonResponse('success', 'Votre message a été envoyé avec succès !');

} catch (Exception $e) {
    // Enregistrer l'erreur dans un fichier de log
    error_log('Erreur d\'envoi d\'email: ' . $mail->ErrorInfo);
    
    // Envoyer une réponse d'erreur générique au client
    sendJsonResponse('error', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard. ' . $mail->ErrorInfo);
}
?>
