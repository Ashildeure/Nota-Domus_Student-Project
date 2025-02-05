<?php
//-----Imports-----

require_once __DIR__ . '/DAO.class.php';

/**
 * `Notification` représente une notification visible sur l'interface.<br>
 * **`CRD`** (pas d'update car par essence, une notification est non-modifiable)
 */
class Notification {
    //-----Attributs-----

    /**
     * @var string Le contenu de la notification (texte)
     */
    private string $message;

    /**
     * @var Person L'utilisateur ayant reçu la notification (objet)
     */
    private Person $owner;

    /**
     * TODO: -> On remplaçerait pas par un booléen à ce moment-là ? Simple proposition
     * TODO: Jetbrains reporte cet attribut comme n'étant jamais lu, juste écrit. Est-ce qu'il sert à quoi que ce soit ?
     * @var string Nature de la notification. Peut être "requestFriend" ou "report"
     */
    private string $action;

    /**
     * @var User|Comment|null Cible de la notification.<br>
     * Si `$action` est "requestFriend" : `$donnee` doit être de type `User`.<br>
     * Si `$action` est "report" : `$donnee` doit être de type `Comment`.
     */
    private User|Comment|null $data; // User si action est requestFriend Comment sinon

    /**
     * @var int Identifiant unique de la notification (tel que dans la base de données)
     */
    private int $id;

    //-----Constructeur-----

    /**
     * Ce constructeur ne doit **jamais** être utilisé dans les contrôleurs.
     * Utiliser createNotification() à la place !
     * @param int $id
     * @param Person $owner
     * @param string $message
     * @param mixed $donee
     */
    public function __construct(int $id, Person $owner, string $message, mixed $donee) {
        $this->id = $id;
        $this->message = $message;
        $this->owner = $owner;
        $this->data = $donee;

        if (gettype($this->data) == 'object' && $this->data::class == "User") { //si data est un object User
            $this->action = "requestFriend";
        } else {
            $this->action = "report";
        }
    }

    //-----Getters-----

    /**
     * @return Comment|User
     */
    public function getData(): User|Comment {
        return $this->data;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getOwner(): Person {
        return $this->owner;
    }

    //-----Setters-----

    public function setAction(string $action): void {
        $this->action = $action;
    }

    //-----Autre-----

    /**
     * Récupère le dernier `id` utilisé par une notification dans la base de données
     * @return int
     */
    public static function getLastId(): int {
        return DAO::get()->quickExecute(
            'SELECT MAX(idNotif) AS nb FROM Notification'
        )[0]['nb'];
    }

    /**
     * Insère une nouvelle entrée de notification dans la base de donnée,
     * et renvoie son équivalent objet `Notification`
     * @param Person $owner
     * @param string $message
     * @param mixed $data
     * @return Notification
     */
    public static function createNotification(Person $owner, string $message, User|Comment|null $data): Notification {
        $dao = DAO::get();
        $query =
            gettype($data) == 'object' && $data::class == 'User'
                // Oui, $data est de type User : la notification est donc une demande d'ami
                ? $dao->prepare('INSERT INTO Notification (idOwner, message, requestFriend) VALUES (:owner, :message, :donee)')
                // Non, $data n'est pas de type User : la notification est donc un signalement
                : $dao->prepare('INSERT INTO Notification (idOwner, message, reportComment) VALUES (:owner, :message, :donee)');

        $query->execute([
            ":owner" => $owner->getId(),
            ":message" => $message,
            ":donee" => $data->getId()
        ]);

        return new Notification(
            self::getLastId(),
            $owner,
            $message,
            $data
        );
    }

    /**
     * Renvoie un objet `Notification` correspondant à son identifiant dans la base de données
     * @param int $id
     * @return Notification
     * @throws RuntimeException|Exception
     */
    public static function readNotification(int $id): Notification {
        $table = DAO::get()->quickExecute(
            'SELECT * FROM Notification WHERE idNotif = :id',
            [':id' => $id]
        );

        if (count($table) == 0) {
            $message = "Notification $id non trouvée dans la base de données";
            error_log($message);
            throw new RuntimeException($message);
        }

        $row = $table[0];
        if (isset($row['requestfriend']) && $row['requestfriend'] != null) {
            $owner = User::readUserById($row['idowner']);
            $requestfriend = User::readUserById($row['requestfriend']);
            $notif = new Notification($row['idnotif'], $owner, $row['message'], $requestfriend);
        } else {
            $owner = User::readUserById($row['idowner']);
            $com = Comment::readComment($row['reportcomment']);
            $notif = new Notification($row['idnotif'], $owner, $row['message'], $com);
        }

        return $notif;
    }

    /**
     * Supprime la notification de la base de données
     * ATTENTION : l'objet correspondant existe toujours !
     * @return void
     */
    public function deleteNotification(): void {
        DAO::get()->quickExecute(
            'DELETE FROM Notification WHERE idNotif = :id',
            [":id" => $this->id]
        );
    }
}