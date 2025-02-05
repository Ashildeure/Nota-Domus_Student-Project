<?php
// Définir les paramètres de l'email
$subject = "Test Email";
$to = "root"; // Destinataire
$from = "root"; // Expéditeur
$body = "Ceci est un test d'email envoyé via Nullmailer.";

// Construire le message à injecter dans Nullmailer
$message = "From: $from\nSubject: $subject\nTo: $to\n\n$body";

// Utiliser la commande nullmailer-inject pour envoyer l'email
$command = 'echo -e "' . $message . '" | nullmailer-inject';

// Exécuter la commande
exec($command, $output, $return_var);

// Vérifier si l'envoi a réussi
if ($return_var === 0) {
    echo "L'email a été envoyé avec succès.";
} else {
    echo "Erreur lors de l'envoi de l'email.";
    // Optionnel : afficher les erreurs
    echo "<pre>";
    print_r($output);
    echo "</pre>";
}