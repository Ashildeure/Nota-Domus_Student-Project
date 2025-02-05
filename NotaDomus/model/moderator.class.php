<?php
//-----Imports-----
require_once __DIR__ . '/person.class.php';
require_once __DIR__ . '/DAO.class.php';
require_once __DIR__ . '/report.class.php';

/**
 * Un modérateur, représenté par la classe `Moderator`, a pour rôle de contrôler les commentaires postés
 * sous les Maisons d'Illustre. C'est une classe dérivée de `Person` : c'est aussi un utilisateur.<br>
 * **`R`** uniquement (on considère que la création, modification, suppression d'un modérateur
 * sont des événements suffisamment exceptionnels pour insérer les données à la main)
 */
class Moderator extends Person {
    //-----Attribut static-----

    /**
     * @var array Liste des commentaires signalés
     * (à mettre à jour avec `readReportedComments`)
     */
    private static array $reportedComments;

    //-----Constructeur-----

    public function __construct(int $id, string $login, string $password, string $email) {
        parent::__construct($login, $password, $email, $id);
    }

    //-----Autre-----

    /**
     * Supprime le commentaire de la base de données
     * @param Comment $comment
     * @return void
     */
    public function deleteComment(Comment $comment): void {
        $comment->deleteComment();
    }

    /**
     * @throws RuntimeException|Exception
     */
    public static function readModerator(int $id): Moderator {
        $result = DAO::get()->quickExecute('            
            SELECT login, password, email
            FROM "User"
            WHERE idUser = :idUser
              AND isModerate = TRUE',
            [':idUser' => $id]
        );

        if (count($result) == 0) {
            $message = "Modérateur $id non trouvée dans la base de données";
            error_log($message);
            throw new RuntimeException($message);
        }

        // cree le Moderator
        return new Moderator(
            $id,
            $result[0]['login'],
            $result[0]['password'],
            $result[0]['email']
        );
    }

    public static function getReportedComments(): array {
        if (!isset(self::$reportedComments))
            self::readReportedComments();
        return self::$reportedComments;
    }

    /**
     * Récupère les commentaires ayant reçu au moins un signalement et les insère dans `$reportedComments`
     * @return void
     * @throws Exception
     */
    private static function readReportedComments(): void {
        $result = DAO::get()->quickExecute('            
            SELECT DISTINCT idcomment
            FROM Report;
        ');
        // Vider la liste déjà existante
        self::$reportedComments = [];
        // La remplir avec les résultats de la requête
        foreach ($result as $comment) {
            self::$reportedComments[] = Comment::readComment($comment['idcomment']);
        }
    }
}