<?php
//-----Imports-----

require_once __DIR__ . '/DAO.class.php';

/**
 * `Person` rassemble les éléments communs à tous les types d'utilisateurs. <br>
 * Tous les utilisateurs sont représentés sous la forme d'objet de type `Person`, qu'ils soient hérités d'une manière ou d'une autre<br>
 * Aucune opération de BD : la classe est abstraite
 */
abstract class Person {
    //-----Attributs-----

    /**
     * @var int Identifiant unique de l'utilisateur (tel que dans la base de données)
     */
    private int $id;

    /**
     * @var string Identifiant de **connexion** de l'utilisateur (login)
     */
    protected string $login;

    /**
     * TODO: C'est pas un problème de stocker le mot de passe comme ça ? Supprimer ce commentaire si pas de problème
     * @var string Mot de passe de cet utilisateur
     */
    protected string $password;

    /**
     * @var string Adresse e-mail de l'utilisateur
     */
    protected string $email;

    //-----Constructeur-----

    /**
     * @throws Exception
     */
    public function __construct(string $login, string $password, string $email, int $id = 0) {
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->id = $id;
    }

    //-----Getters-----

    public function getId(): int {
        return $this->id;
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Renvoie le rôle de l'utilisateur
     * @return string compris dans ('moderator', 'ohAdmin', 'user')
     */
    public function getRole(): string {
        $dao = DAO::get();
        $table = $dao->quickExecute(
            'SELECT manage, isModerate FROM "User" WHERE idUser = :iduser',
            [":iduser" => $this->id]
        )[0];
        $moderate = $table['ismoderate'];
        $manage = $table['manage'];

        if ($moderate) {
            return 'moderator';
        } else if (!is_null($manage)) {
            return 'ohAdmin';
        } else {
            return 'user';
        }
    }

    //-----Setters-----

    /**
     * @throws Exception
     */
    public function setLogin(string $login): void {
        // Pas de quickExecute ici car on réutilise la variable $query dans la clause if
        $query = DAO::get()->prepare('SELECT * FROM ProhibitedLogin WHERE login = :login');
        $query->execute([":login" => $login]);
        if ($query->rowCount() == 0) {
            $this->login = $login;
        } else {
            throw new RuntimeException("Login interdit");
        }
    }

    /**
     * @throws Exception
     */
    public function setPassword(string $password): void {
        $queryResult = DAO::get()->quickExecute(
            'SELECT password FROM "User" WHERE password = :password',
            [":password" => $password]
        );

        if (count($queryResult) > 0) {
            error_log('Un mot de passe identique à l\'ancien a été choisi par un utilisateur.');
            throw new RuntimeException('Vous devez choisir un mot de passe différent de l\'ancien !');
        }

        $this->password = $password;
    }

    /**
     * @throws RuntimeException
     */
    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)
            || count(DAO::get()->quickExecute(
                'SELECT * FROM ProhibitedEmail WHERE mail = :email',
                [':email' => $email]
            )) != 0) {
            error_log('Une adresse email invalide ou interdite a été choisie par un utilisateur.');
            throw new RuntimeException('Cette adresse email est invalide ou bannie.');
        }

        $this->email = $email;
    }

    //-----Autre-----

    /**
     * Récupère le dernier `id` utilisé dans la base de données
     * @return int
     */
    public function getLastId(): int {
        return DAO::get()->quickExecute('            
            SELECT MAX(idUser) AS nb
            FROM "User"
        ')[0]['nb'];
    }

    public static function emailIsAvalaible(User $user, string $email): bool {
        $dao = DAO::get();
        $table = $dao->quickExecute("SELECT * FROM prohibitedemail WHERE mail = :email", [":email" => $email]);
        if(count($table) >= 1){
            return false;
        }
        else{
            $table = $dao->quickExecute('SELECT * FROM "User" WHERE iduser != :id AND email = :email', [":id" => $user->getId(), ":email" => $email]);
            if(count($table) >= 1){
                return false;
            }else{
                return true;
            }
        }
    }

}