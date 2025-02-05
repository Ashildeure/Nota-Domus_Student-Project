<?php
//-----Imports-----

require_once __DIR__ . '/DAO.class.php';
require_once __DIR__ . '/user.class.php';
require_once __DIR__ . '/house.class.php';

/**
 * `OhAdmin` représente les gérants de maison d'illustre.
 * Il hérite de `User` qui elle-même hérite de `Person`<br>
 * **`CRU`** (`delete` hérite de `User` et n'a donc pas besoin d'être redéfini)
 */
class OhAdmin extends User {
    //-----Attributs-----

    /**
     * @var int l'id de la maison dont cet admin est propriétaire
     */
    private int $idhouse;

    /**
     * @var House La maison dont cet admin est propriétaire
     */
    private House $house;

    //-----Constructeur-----

    public function __construct(int $id, string $login, string $password, string $email, int $blame, int $idhouse) {
        parent::__construct($id, $login, $password, $email, $blame);
        $this->idhouse = $idhouse;
    }

    //-----Getters-----

    /**
     * @throws Exception
     */
    public function getHouse(): House {
        if (!isset($this->house))
            $this->house = House::readHouse($this->idhouse);
        return $this->house;
    }

    //-----Autre-----

    /**
     * Ajoute une nouvelle entrée à la base de données correspondant à ce gestionaire de maison d'illustre
     * @param string $login
     * @param string $password
     * @param string $email
     * @param int $idHouse
     * @return void
     */
    public static function createOhAdmin(string $login, string $password, string $email, int $idHouse): void {
        DAO::get()->quickExecute('            
            INSERT INTO "User" (password, blame, email, login, isModerate, manage)
            VALUES (:password, 0, :email, :login, FALSE, :house)',
            [
                ":login" => $login,
                ":password" => password_hash(
                    $password,
                    PASSWORD_ARGON2ID,
                    [
                        'memoty_cost' => 1 << 17, // 128 Mo de mémoire
                        'time_cost' => 4, // 4 itérations
                        'threads' => 2 // 2 threads
                    ]
                ),
                ":email" => $email,
                ":house" => $idHouse
            ]
        );
    }

    /**
     * Recupere les infos d'un UohAdmin identifier par sont id dans la DB
     * @param int $id
     * @return OhAdmin
     * @throws RuntimeException|Exception
     */
    public static function readOhAdmin(int $id): OhAdmin {
        $result = DAO::get()->quickExecute('
            SELECT manage, login, password, email, blame
            FROM "User"
            WHERE idUser = :idUser',
            [':idUser' => $id]
        );

        if (count($result) == 0) {
            $message = "OhAdmin $id non trouvé dans la base de données";
            error_log($message);
            throw new RuntimeException($message);
        }

        // La colonne blame peut valoir null. On s'assure ici qu'elle ait une valeur.
        if (!isset($result[0]['blame']))
            $result[0]['blame'] = 0;

        $result = $result[0];
        // cree l'`OhAdmin`
        return new OhAdmin(
            $id,
            $result['login'],
            $result['password'],
            $result['email'],
            $result['blame'],
            $result['manage']
        );
    }

    /**
     * Met a jour la base de donnée avec les infos de l'objet courant
     * @return void
     * @throws Exception
     */
    public function updateOhAdmin(): void {
        DAO::get()->quickExecute('            
            UPDATE "User"
            SET password = :password,
                blame    = :blame,
                email    = :email,
                login    = :login,
                manage   = :house
            WHERE idUser = :idUser',
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
                ':iduser' => $this->getId(),
                ':house' => $this->getHouse()->getId()
            ]
        );
    }

    // La méthode `delete` hérite de `User`. Elle n'a donc pas besoin d'être redefinie
}