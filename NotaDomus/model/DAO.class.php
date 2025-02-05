<?php

/**
 * Le DAO : Data Access Object.<br>
 * Représente la base de donnée et permet d'interagir avec celle-ci
 */
class DAO {
    //-----Attributs-----

    /**
     * @var string Adresse IP ou nom de domaine du serveur de base de données
     */
    private const HOST = '192.168.14.230';

    /**
     * @var string Port du serveur de base de données
     */
    private const PORT = '5432';

    /**
     * @var string Nom de la base de donnée (id nominal)
     */
    private static string $dbname = 'notadomus';

    /**
     * @var string Utilisateur (profil) utilisé pour se connecter à la base de données
     */
    private const USER = 'riondetf';

    /**
     * TODO: A terme, il faudra changer ça. C'est pas sécurisé de coder en dur le mdp.
     * @var string Mot de passe de la connexion à la base de données.
     */
    private const PASSWORD = 'mdpflav';

    /**
     * @var DAO|null Le singleton de la classe : l'unique objet
     */
    private static ?DAO $instance = null;

    /**
     * @var PDO L'objet local PDO de la base de donnée
     */
    private PDO $pdo;

    /**
     * @var string Le type, le chemin et le nom de la base de donnée
     */
    private string $database;

    //-----Constructeur-----

    /**
     * Chargé d'ouvrir la BD.<br>
     * **ATTENTION: le constructeur est privé pour éviter de créer par erreur un objet DAO.**
     * Pour acceder à l'unique objet DAO, utiliser la méthode de classe "get".
     * @throws RuntimeException
     */
    private function __construct(string $databaseName = null) {
        try {
            if ($databaseName !== null)
                self::$dbname = $databaseName;
            // Construire le Data Source Name à partir des constantes de classe
            $this->database = 'pgsql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::$dbname;

            $this->pdo = new PDO($this->database, self::USER, self::PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            $message = "Erreur PDO : " . $e->getMessage() . ' lors de la connexion à ' . $this->database;
            error_log($message);
            throw new RuntimeException($message);
        }
    }

    //-----Autre-----

    /**
     * Méthode statique pour acceder au singleton. Renvoie l'instance unique de du DAO.
     * @return DAO
     */
    public static function get(): DAO {
        // Si l'objet n'a pas encore été crée, le crée
        if (self::$instance === null)
            self::$instance = new DAO($GLOBALS['databaseName']);
        return self::$instance;
    }

    /**
     * Encapsule la préparation de la requête du PDO.
     * @param string $query
     * @return PDOStatement
     */
    public function prepare(string $query): PDOStatement {
        try {
            // Utiliser le prepare du PDO
            $request = $this->pdo->prepare($query);
            // Si celui-ci renvoie false c'est qu'il y a eu une erreur, et c'est tout ce que l'on sait.
            if (!$request)
                die("PDO query Error on \"$query\"");
        } catch (PDOException $e) {
            // Si on arrive ici, on s'arrête et on affiche le message de la PDOException
            die("PDO query Error on \"$query\" " . $e->getMessage());
        }
        return $request;
    }

    /**
     * Prépare une requête puis l'exécute successivement. <br>
     * Il suffit d'appeler cette fonction une seule fois pour la plupart des situations.
     * @param string $query
     * @param array $params
     * @return array
     */
    public function quickExecute(string $query, array $params = []): array {
        $statement = $this->prepare($query);
        $statement->execute($params);
        try {
            return $statement->fetchAll();
        } catch (Throwable) {
            // A noter : il me semble que fetchAll ne lève jamais d'exception. C'est juste une précaution.
            // Je suis pas sûr de comment ça fonctionne, je regarde ça une autre fois...
            // Ça me paraît simplement une bonne idée de faire des fichiers de log comme en Java.
            error_log('Impossible de récupérer les données de sortie', LOG_WARNING);
            return [];
        }
    }

}
