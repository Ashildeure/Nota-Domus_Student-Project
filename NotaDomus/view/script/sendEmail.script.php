<?php
$to = "mailserverflav@gmail.com";
$from = "mailserverflav@gmail.com";
$subject = "Test Email";
$body = "Ceci est un test d'email envoyé via Nullmailer.";

// Assurez-vous que chaque ligne est correctement terminée par \r\n
$message = "To: $to\r\n";
$message .= "From: $from\r\n";
$message .= "Subject: $subject\r\n";
$message .= "\r\n";  // Ligne vide séparant les headers du corps du message
$message .= $body;

// Commande Nullmailer avec échappement des caractères spéciaux
$command = "echo -e " . "To: mailserverflav@gmail.com\nFrom: mailserverflav@gmail.com\nSubject: Test Email\n\nCeci est un test d'email envoyé via Nullmailer." . "| nullmailer-inject";

exec($command, $output, $return_var);

if ($return_var === 0) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de l'envoi de l'email", "output" => $output, "return_var" => $return_var]);
}