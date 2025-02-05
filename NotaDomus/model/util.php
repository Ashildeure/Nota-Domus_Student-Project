<?php
/**
 * Renvoie une chaîne de caractères représentant la note en argument.
 * Celle-ci est formatée avec 5 caractères ★ ou ☆ selon la note.
 * @param float $rating
 * @return string
 */
function starFormat(float $rating): string {
    return str_repeat('★', ceil($rating))
        . str_repeat('☆', 5 - ceil($rating));
}