<?php
//---Inclusion des partie du modele uttiliser
require_once __DIR__ . '/DAO.class.php';
require_once __DIR__ . '/comment.class.php';
require_once __DIR__ . '/user.class.php';


class Report {
    /**
     * @var User l'user qui a fait le signalement, ou si int l'id qui l'identifie
     */
    private int|User $flagger;
    /**
     * @var string categorie de signalement parmit les suivantes : Propos insultants,Désinformation,Mauvaise maison ou Autre
     */
    private string $categorie;

    /**
     * @var string texte pour expliquer la raison du signalment plus explixitement
     */
    private string $content;

    public function __construct(User|int $flagger, string $categorie, string $content = "") {
        // Gestion pour construction grace a un object user ou un id qui reference un user
        $this->flagger = $flagger;
        $this->categorie = $categorie;
        $this->content = $content;
    }

    //-----Getteur-----

    /**
     * Donne l'user qui a fait le signalement
     * @return User
     * @throws Exception
     */
    public function getFlagger(): User {
        if (!is_object($this->flagger)) {
            $this->flagger = User::readUserById($this->flagger);
        }
        return $this->flagger;
    }

    public function getCategorie(): string {
        return $this->categorie;
    }

    public function getContent(): string {
        return $this->content;
    }

    //-----CRUD----

    /**
     * Insert dans la base de donne et cre un object Report
     * @param Comment|int $reportCom
     * @param User|int $flagger
     * @param string $categorie
     * @param string $content
     * @return Report
     */
    public static function createReport(Comment|int $reportCom, User|int $flagger, string $categorie, string $content = ""): Report {
        // Gestion du type des paramettres
        if (is_object($flagger)) {
            $flagger = $flagger->getId();
        }
        if (is_object($reportCom)) {
            $reportCom = $reportCom->getId();
        }

        // Insert dans la DB
        DAO::get()->quickExecute(
            'INSERT INTO report VALUES (:com, :flagger, :categorie, :content)',
            [
                ':com' => $reportCom,
                ':flagger' => $flagger,
                ':categorie' => $categorie,
                ':content' => $content
            ]
        );
        // creation de l'object
        return new Report($flagger, $categorie, $content);
    }

    /**
     * Lit le report de la DB qui corespond a ce signaleur et ce commentaire
     * @param Comment|int $reportCom
     * @param User|int $flagger
     * @return Report
     */
    public static function readReport(Comment|int $reportCom, User|int $flagger): Report {
        // Gestion du type des paramettres
        if (is_object($flagger)) {
            $flagger = $flagger->getId();
        }
        if (is_object($reportCom)) {
            $reportCom = $reportCom->getId();
        }

        // Lecture dans la DB
        $report = DAO::get()->quickExecute('SELECT * FROM report WHERE idComment = :com AND idFlagger = :flagger',
            [
                ':com' => $reportCom,
                ':flagger' => $flagger
            ]
        );
        // Gestion d'erreur si la base est incoherente
        if (count($report) > 1) {
            $message = 'Erreur lors de `Report::readReport`  : le commentaire ' . $reportCom . ' a ete report plusieur fois par ' . $flagger;
            error_log($message);
            throw new RuntimeException($message);
        }
        if (count($report) == 0) {
            $message = "Erreur dans `Report::readReport` : le " . $reportCom . ' n\'as jamais ete signaler par l\'user' . $flagger;
            error_log($message);
            throw new RuntimeException($message);
        }
        //Creation de l'object
        return new Report($flagger, $report[0]['categorie'], $report[0]['content']);
    }

    /**
     * Lit tout le report d'un commentaire
     * @param Comment|int $reportCom
     * @return array de Report
     */
    public static function readAllReport(Comment|int $reportCom): array {
        // Gestion du type du paramettres
        if (is_object($reportCom)) {
            $reportCom = $reportCom->getId();
        }
        // Lecture dans la DB
        $reports = DAO::get()->quickExecute('SELECT * FROM report WHERE idComment = :com',
            [
                ':com' => $reportCom
            ]
        );
        // Traitement du resultat de la requete
        $result = [];
        foreach ($reports as $report) {
            $result[] = new Report($report['idflagger'], $report['categorie'], $report['content']);
        }

        return $result;
    }

    /**
     * supprime dans la BD le Report identifier par le signaleur et le commentaire
     * @param Comment|int $reportCom
     * @param User|int $flagger
     * @return void
     */
    public static function deleteReport(Comment|int $reportCom, User|int $flagger) {
        // Gestion du type des paramettres
        if (is_object($flagger)) {
            $flagger = $flagger->getId();
        }
        if (is_object($reportCom)) {
            $reportCom = $reportCom->getId();
        }

        // suppression dans la DB
        DAO::get()->quickExecute('DELETE FROM report WHERE idComment = :com AND idflagger = :flagger',
            [
                ':com' => $reportCom,
                ':flagger' => $flagger
            ]
        );
    }

    /**
     * Supprime tout les report liee a un commentaire
     * @param Comment|int $reportCom
     * @return void
     */
    public static function deleteAllReport(Comment|int $reportCom) {
        // Gestion du type du paramettres
        if (is_object($reportCom)) {
            $reportCom = $reportCom->getId();
        }
        // Suppression dans la DB
        DAO::get()->quickExecute('DELETE FROM report WHERE idcomment=:com',
            [
                ':com' => $reportCom
            ]
        );
    }
}
