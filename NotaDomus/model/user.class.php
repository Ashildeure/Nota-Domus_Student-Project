<?php
//-----Imports-----

require_once __DIR__ . '/DAO.class.php';
require_once __DIR__ . '/person.class.php';
require_once __DIR__ . '/report.class.php';
require_once __DIR__ . '/comment.class.php';
require_once __DIR__ . '/util.php';

/**
 * `User` représente les utilisateurs sans droit particulier supplémentaire.
 * Il est dérivé de la classe mère `Person`.<br>
 * **`CRUD`** Complet
 */
class User extends Person {
    //-----Attributs-----

    /**
     * @var int Identifiant unique de l'utilisateur (tel que dans la base de données)
     */
    private int $id;

    /**
     * @var array Liste des commentaires postés par cet utilisateur, toutes maisons confondues (liste d'objets `Comment`)
     */
    public array $comments;

    /**
     * @var int Nombre de blâmes de cet utilisateur
     */
    private int $blame;

    /**
     * @var array Liste d'amis de l'utilisateurs (liste d'objets `Person`)
     */
    private array $friends;

    /**
     * @var array Liste de maisons favorites de l'utilisateur (liste d'objets `House`)
     */
    private array $favorites;

    /**
     * @var array Liste des notifications que l'utilisateur a reçu (liste d'objets `Notification`)
     */
    private array $notifications;

    public function __construct(int $id, string $login, string $password, string $email, int $blame) {
        parent::__construct($login, $password, $email, $id);
        $this->id = $id;
        $this->blame = $blame;
        $this->friends = [];
        $this->favorites = [];
        $this->comments = [];
        $this->notifications = [];
    }

    //-----Getters------

    public function getBlame(): int {
        return $this->blame;
    }

    /**
     * @throws RuntimeException|Exception
     */
    public function getFriends(): array {
        $this->readFriendOfUser();
        return $this->friends;
    }

    public function getFavorites(): array {
        if (empty($this->favorites))
            $this->readFavorites($this->id);
        return $this->favorites ?? [];
    }

    public function getComments(): array {
        if (empty($this->comments))
            $this->readComments();
        return $this->comments;
    }

    /**
     * @throws Exception
     */
    public function getNotifications(): array {
        if (empty($this->notifications))
            $this->readNotifications();
        return $this->notifications;
    }

    //-----Autre-----

    /**
     * Détermine si le mot de passe en argument est valide ou non.
     * Un mot de passe est valide s'il contient au moins :
     * - 8 caractères au total
     * - Une lettre majuscule
     * - Une lettre minuscule
     * - Un chiffre
     * - Un caractère spécial
     * @param string $password
     * @return bool
     */
    private static function isPasswordValid(string $password): bool {
        // Longueur minimum
        if (strlen($password) < 8)
            return false;

        // Au moins une majuscule, une minuscule, un chiffre, et un caractère spécial
        if (!preg_match('/[A-Z]/', $password))
            return false;
        if (!preg_match('/[a-z]/', $password))
            return false;
        if (!preg_match('/[0-9]/', $password))
            return false;
        if (!preg_match('/[\W_]/', $password))
            return false;

        // Optionnel : regarder dans un fichier qui contient une liste des mots de passe les plus communs
        // $commonPasswords = file('common_passwords.txt', FILE_IGNORE_NEW_LINES);
        // if (in_array($password, $commonPasswords)) return false;

        // Si on est arrivé ici, c'est que toutes les vérifications sont passées : on est sûr que le mot de passe est valide
        return true;
    }

    /**
     * Incrémente `$blame`
     * @return void
     */
    public function addBlame(): void {
        $this->blame++;
    }

    /**
     * Si un utilisateur avec un tel `$email` existe dans la base de donnée, renvoie `true`.
     * Sinon, renvoie `false`.
     * @param string $email
     * @return bool
     */
    public static function userExists(string $email): bool {
        try {
            $result = DAO::get()->quickExecute(
                'SELECT * FROM "User" WHERE email = :email',
                [':email' => $email]
            );

            // Vérifie si des résultats sont retournés
            return $result && count($result) > 0; // Si l'expression est évaluée à `true`, l'utilisateur existe
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de l'utilisateur : " . $e->getMessage());
            return false; // En cas d'erreur, retourne `false`
        }
    }

    /**
     * Informe de la présence de la `House` en argument dans les favoris de l'utilisateur
     * @param House $house
     * @return true si la house et en favori
     * @throws Exception
     */
    public function isInFavorite(House $house): bool {
        if (empty($this->favorites))
            $this->getFavorites();
        return in_array($house, $this->favorites);
    }

    /**
     * Bannit l'utilisateur de la plateforme. Cela inclut la suppression de son compte de la base de données, ainsi que :
     * - L'ajout de son adresse e-mail à la liste des e-mails interdits (dans la base de données)
     * - L'ajout de son identifiant de login à la liste des logins interdits (dans la base de données)
     * @return void
     */
    public function ban(): void {
        $dao = DAO::get();

        $dao->quickExecute(
            'INSERT INTO ProhibitedEmail VALUES (:mail) ON CONFLICT DO NOTHING',
            ['mail' => $this->email]
        );

        $dao->quickExecute(
            'INSERT INTO ProhibitedLogin VALUES (:login) ON CONFLICT DO NOTHING',
            ['login' => $this->login]
        );

        $this->email = '';
        $this->login = '';
        $this->deleteUser();
    }

    /**
     * Ajoute un nouveau commentaire à la liste des commentaires de l'utilisateur. <br>
     * **ATTENTION : cette méthode ne modifie pas la base de données,
     * et ne met pas à jour la liste des commentaires de la Maison d'Illustres, ni quoi que ce soit d'autre.**
     * @param House $house
     * @param User $user
     * @param string $content
     * @param float $rate
     * @param int $like
     * @param int $dislike
     * @param int $reporting
     * @return void
     * @throws Exception
     */
    public function addComment(House $house, User $user, string $content, float $rate = 0, int $like = 0, int $dislike = 0): void {
        $this->comments[] = new Comment($this->id, $user->getId(), $house->getId(), $content, $rate, $like, $dislike, false);
        // TODO: L'id, c'est celui du commentaire, pas de la maison. Est-ce qu'il vaut mieux pas insérer un nouvel élément dans la base, puis le lire et en faire un objet après coup ?
        // De manière générale, je ne suis pas sûr que cette méthode soit bien utile.
    }

    /**
     * Ajoute un utilisateur en ami et répercute les modifications dans la base de données.
     * @param User $otherUser
     * @return void
     */
    public function addFriend(User $otherUser): void {
        // Vérifie si déjà amis pour éviter les doublons
        if ($this->isFriendWith($otherUser))
            return;

        // Ajouter l'ami dans l'objet actuel
        $this->friends[$otherUser->getLogin()] = $otherUser;

        // Ajouter réciproquement dans l'objet de l'autre utilisateur
        $otherUser->addToFriendsList($this);

        // Insérer dans la base de données
        $this->createFriend($otherUser);
        $otherUser->createFriend($this);

    }

    /**
     * Ajoute `$this` et `$otherUser` en ami uniquement dans la base de données<br>
     * Ajoute une nouvelle entrée dans la BD Friend avec `idUser1 = $this->getId()` et `idUser2 = $otherUser->getId()`
     * @param User $otherUser
     * @return void
     */
    public function createFriend(User $otherUser): void {
        DAO::get()->quickExecute('
            INSERT INTO Friend (idUser1, idUser2)
            VALUES (:user, :other)',
            [
                ":user" => $this->getId(),
                ":other" => $otherUser->getId()
            ]
        );
    }

    /**
     * Vérifie si l'utilisateur est déjà ami avec un autre utilisateur
     * @param User $otherUser
     * @return bool
     */
    public function isFriendWith(User $otherUser): bool {
        return isset($this->friends[$otherUser->getLogin()]);
    }

    /**
     * Ajoute un ami dans la liste sans mise à jour de la base de données
     * @param User $otherUser
     * @return void
     */
    private function addToFriendsList(User $otherUser): void {
        if (!$this->isFriendWith($otherUser))
            $this->friends[$otherUser->getLogin()] = $otherUser;
    }

    /**
     * Ajoute la maison spécifiée aux favoris de l'utilisateur dans la base de données.
     * @param House $house
     * @return void
     */
    public function addFavorite(House $house): void {
        $this->favorites[$house->getName()] = $house;
        DAO::get()->quickExecute('
            INSERT INTO favorite (idowner, idhouse)
            VALUES (:idowner, :idhouse)',
            [
                ':idowner' => $this->id,
                ':idhouse' => $house->getId()
            ]
        );
    }

    /**
     * Ajoute une nouvelle entrée à la base de données correspondant à cet utilisateur.<br>
     * Cette méthode vérifie aussi si le mot de passe est valide avant insertion.
     * @param string $password
     * @param string $email
     * @param string $login
     * @return void
     */
    public static function createUser(string $password, string $email, string $login): void {
        // Validate password
        if (!self::isPasswordValid($password)) {
            error_log('Un utilisateur a tenté de s\'inscrire avec un mot de passe non conforme aux critères de sécurité');
            throw new RuntimeException("Le mot de passe n'est pas conforme au critères de sécurité.");
        }
        $options = [
            'memoty_cost' => 1 << 17, // 128 Mo de mémoire
            'time_cost' => 4, // 4 itérations
            'threads' => 2 // 2 threads
        ];

        DAO::get()->quickExecute('            
            INSERT INTO "User" (password, email, login, isModerate, blame)
            VALUES (:password, :email, :login, FALSE, 0)',
            [
                ':password' => password_hash($password, PASSWORD_ARGON2ID, $options),
                ':email' => $email,
                ':login' => $login
            ]
        );
    }

    /**
     * Met à jour la liste d'amis de l'utilisateur à partir de la base de données
     * @return void
     * @throws RuntimeException|Exception
     */
    private function readFriendOfUser(): void {
        $table = DAO::get()->quickExecute('
            SELECT idUser2
            FROM Friend
            WHERE idUser1 = :idUser',
            [':idUser' => $this->id]
        );

        // Vider le tableau pour ne pas créer de doublon
        $this->friends = [];
        // Le remplir à nouveau
        foreach ($table as $friend)
            $this->friends[] = User::readUserById($friend['iduser2']);
    }

    /**
     * Recupère les infos d'un User à partir de son id dans la base de données.
     * Ceci crée l'objet correspondant et le renvoie.
     * @param int $idUser
     * @return User
     * @throws RuntimeException|Exception
     */
    public static function readUserById(int $idUser): User {
        $result = DAO::get()->quickExecute('
            SELECT login, password, email, blame
            FROM "User"
            WHERE idUser = :idUser',
            [':idUser' => $idUser]
        );

        if (count($result) == 0) {
            $message = "Utilisateur $idUser non trouvé dans la base de données";
            error_log($message);
            throw new RuntimeException($message);
        }

        if (!isset($result[0]['blame']))
            $result[0]['blame'] = 0;

        $result = $result[0];
        // cree l'User
        return new User(
            $idUser,
            $result['login'],
            $result['password'],
            $result['email'],
            $result['blame']
        ); // user(login, password, blame)
    }

    /**
     * Renvoie un `User` à partir de son `$login` dans la base de données
     * @param string $login
     * @return User
     * @throws RuntimeException|Exception
     */
    public static function readUserByLogin(string $login): User {
        $result = DAO::get()->quickExecute('
            SELECT iduser, login, password, email, blame
            FROM "User"
            WHERE login = :login',
            [':login' => $login]
        );

        if (count($result) == 0) {
            $message = "Utilisateur $login non trouvé dans la base de données";
            error_log($message);
            throw new RuntimeException($message);
        }

        $result = $result[0];
        return new User(
            $result['iduser'],
            $result['login'],
            $result['password'],
            $result['email'],
            $result['blame']
        ); // user(login, password, blame)
    }


    /**
     * @throws RuntimeException|Exception
     */
    public static function readUserByEmail(string $email): User {
        $result = DAO::get()->quickExecute('
            SELECT iduser, login, password, email, blame
            FROM "User"
            WHERE email = :email',
            [':email' => $email]
        );

        if (count($result) == 0) {
            $message = "Utilisateur avec l'email $email non trouvé dans la base de données";
            error_log($message);
            throw new RuntimeException($message);
        }

        $result = $result[0];
        // cree l'User
        return new User(
            $result['iduser'],
            $result['login'],
            $result['password'],
            $result['email'],
            $result['blame']
        ); // user(login, password, blame)
    }

    /**
     * Mets à jour la liste de maisons favorites de l'utilisateur à partir de la base de données
     * @param $id
     * @return void
     */
    private function readFavorites($id): void {
        $table = DAO::get()->quickExecute('
            SELECT idHouse
            FROM Favorite
            WHERE idOwner = :id',
            [":id" => $id]
        );
        // Vider la liste des favoris et la remplir à nouveau
        $this->favorites = [];
        foreach ($table as $favorite)
            $this->favorites[] = House::readHouse($favorite['idhouse']);
    }

    /**
     * Mets à jour la liste de commentaires de l'utilisateur à partir de la base de données
     * @return void
     */
    private function readComments(): void {
        $result = DAO::get()->quickExecute('
            SELECT idCom
            FROM Comment
            WHERE idOwner = :idUser',
            [':idUser' => $this->id]
        );
        // Vider la liste des commentaires et la remplir à nouveau
        $this->comments = [];
        foreach ($result as $comment)
            $this->comments[] = Comment::readComment($comment['idcom']);
    }

    /**
     * Mets à jour la liste de notifications de l'utilisateur à partir de la base de données
     * @return void
     * @throws RuntimeException|Exception
     */
    private function readNotifications(): void {
        $table = DAO::get()->quickExecute('
            SELECT *
            FROM Notification
            WHERE idOwner = :id',
            [':id' => $this->id]
        );

        $this->notifications = [];
        foreach ($table as $notification)
            $this->notifications[] = Notification::readNotification($notification['idnotif']);
    }

    /**
     * Permet de lire des nom d'user similaire a la recherche (ne retourne pas l'user qui l'appel)
     * @param string $research
     * @return array
     * @throws Exception
     */
    public function readLike(string $research): array {
        $users = [];
        $dao = DAO::get();
         $table = $dao->quickExecute('
                SELECT u.iduser
                FROM "User" u
                LEFT JOIN friend f ON u.iduser = f.iduser2 AND f.iduser1 = :id
                WHERE u.login ILIKE :research
                  AND u.iduser != :id
                  AND f.iduser2 IS NULL;'
         ,[":research" => "%$research%", ":id" => $this->id]);
        foreach ($table as $user) {
            $users[] = self::readUserById($user['iduser']);
        }

        return $users;
    }

    /**
     * Sauvegarde toute les information de l'uttilisateur
     * @return void
     */
    public function updateUser(): void {
        DAO::get()->quickExecute('
            UPDATE "User"
            SET password = :password, blame = :blame, email = :email, login = :login
            WHERE idUser = :iduser',
            [
                ':password' => password_hash(
                    $this->getPassword(),
                    PASSWORD_ARGON2ID,
                    [
                        'memoty_cost' => 1 << 17, // 128 Mo de mémoire
                        'time_cost' => 4, // 4 itérations
                        'threads' => 2 // 2 threads
                    ]
                ),
                ':blame' => $this->getBlame(),
                ':email' => $this->getEmail(),
                ':login' => $this->getLogin(),
                ':iduser' => $this->getId()
            ]
        );
    }

    /**
     * Supprime cet utilisateur de la base de données.<br>
     * **ATTENTION : Ne supprime pas l'objet en lui-même**<br>
     * Cette méthode supprime aussi toutes les associations
     * dont cet utilisateur fait partie dans la table Friend.
     * @return void
     */
    public function deleteUser(): void {
        $this->deleteAllFriends();
        DAO::get()->quickExecute('
            DELETE FROM "User"
                   WHERE idUser = :idUser',
            [':idUser' => $this->getId()]
        );
    }

    /**
     * Supprime la maison spécifiée des favoris de l'utilisateur. <br>
     * @param House $house
     * @return void
     */
    public function deleteFavorite(House $house): void {
        unset($this->favorites[$house->getName()]);
        DAO::get()->quickExecute('
            DELETE FROM favorite
            WHERE idowner = :idowner AND idhouse = :idhouse',
            [
                ':idowner' => $this->getId(),
                ':idhouse' => $house->getId()
            ]
        );
    }

    /**
     * Si le commentaire spécifié appartient à cet utilisateur, supprime **l'association** entre le commentaire et l'utilisateur.<br>
     * **ATTENTION : Cela ne supprime pas le commentaire en lui-même, et ne répercute *aucun* changement sur la base de données.**<br>
     * TODO: il faut discuter de l'intérêt de cette méthode. Un commentaire est référencé par plusieurs objets...
     * @param Comment $comment
     * @return void
     */
    public function deleteComment(Comment $comment): void {
        $key = array_search($comment, $this->getComments());
        if ($key !== false)
            // Suppression du commentaire s'il existe
            unset($this->getComments()[$key]);
    }

    /**
     * Supprime l'utilisateur spécifié de la liste d'amis. <br>
     * **ATTENTION : Aucun changement n'est répercuté dans la base de données.**
     * @param User $otherUser
     * @return void
     * @throws Exception
     */
    public function removeFriend(User $otherUser): void {
        $this->deleteFriend($otherUser);
        // Supprimer $this de la liste d'amis de $otherUser si présent
        // if (isset($otherUser->getFriends()[$this->getLogin()])) {
        //     $otherUser->deleteFriend($this);
        //     unset($otherUser->friends[$this->getLogin()]); // Suppression directe
        // }

        // // Supprimer $otherUser de la liste d'amis de $this
        // if (isset($this->friends[$otherUser->getLogin()])) {
        //     $this->deleteFriend($otherUser);
        //     unset($this->friends[$otherUser->getLogin()]);
        // }
    }

    /**
     * Supprime tous les amis de cet utilisateur.<br>
     * **ATTENTION : Aucun changement n'est répercuté dans la base de données.**
     * @return void
     * @throws Exception
     */
    private function removeAllFriends(): void {
        foreach ($this->friends as $friend)
            $this->removeFriend($friend);
    }

    /**
     * Delete dans la BD la ligne où `$this` est `idUser1` et `$otherUser` `idUser2`
     * @param User $otherUser
     * @return void
     */
    public function deleteFriend(User $otherUser): void {
        DAO::get()->quickExecute('            
            DELETE
            FROM Friend
            WHERE (idUser1 = :idUser1
                AND idUser2 = :idUser2) 
              OR (idUser1 = :idUser2
                AND idUser2 = :idUser1)',
            [
                ':idUser1' => $this->getId(),
                ':idUser2' => $otherUser->getId()
            ]
        );
    }

    /**
     * Supprime toutes les amitiés (les associations dans la table Friend,
     * pas les amis en tant que tels) de cet utilisateur dans la base de données
     * @return void
     */
    public function deleteAllFriends(): void {
        DAO::get()->quickExecute('            
            DELETE
            FROM Friend
            WHERE idUser1 = :id
               OR idUser2 = :id',
            [':id' => $this->getId()]
        );
    }
}