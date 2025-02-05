<?php
//-----Imports-----

require_once __DIR__ . '/DAO.class.php';
require_once __DIR__ . '/user.class.php';
require_once __DIR__ . '/house.class.php';
require_once __DIR__ . '/report.class.php';
require_once __DIR__ . '/util.php';

/**
 * La classe `Comment` représente un commentaire posté sous la page d'une maison d'illustre.<br>
 * **`CRUD`** Complet
 */
class Comment {
    //-----Attributs-----

    /**
     * @var int L'identifiant unique du commentaire
     */
    private int $id;

    /**
     * @var int id de l'user a qui appartient le commentaire
     */
    private int $idOwner;

    /**
     * @var User L'objet User correspondant à la personne ayant écrit le commentaire
     */
    private User $owner;

    /**
     * @var int id de l'house qui est concerne
     */
    private int $idHouse;
    /**
     * @var House La maison d'illustre (objet House) sous laquelle est posté le commentaire
     */
    private House $house;

    /**
     * @var string Contenu du commentaire sous forme de texte
     */
    private string $content;

    /**
     * @var float Note attribuée à la maison d'illustre au travers de ce commentaire
     */
    private float $rate;

    /**
     * @var int Nombre de mentions "j'aime" sur ce commentaire
     */
    private int $likes;

    /**
     * @var int Nombre de mentions "je n'aime pas" sur ce commentaire
     */
    private int $dislikes;

    /**
     * @var array Liste de report qui concerne ce commetaire
     */
    private array $reporting;

    /**
     * @var bool Indicateur de la modification du commentaire
     */
    private bool $edit;

    //-----Constructeur-----

    public function __construct(int $id, int $idOwner, int $idHouse, string $content, float $rate, int $likes, int $dislikes, bool $edit) {
        $this->id = $id;
        $this->idOwner = $idOwner;
        $this->idHouse = $idHouse;
        $this->content = $content;
        //$this->setContent($content);
        $this->rate = $rate;
        $this->likes = $likes;
        $this->dislikes = $dislikes;
        $this->edit = $edit;
    }

    //-----Getters-----

    public function getId(): int {
        return $this->id;
    }

    /**
     * @throws Exception
     */
    public function getOwner(): User {
        if (!isset($this->owner))
            $this->owner = User::readUserById($this->idOwner);
        return $this->owner;

    }

    /**
     * @throws Exception
     */
    public function getHouse(): House {
        if (!isset($this->house))
            $this->house = House::readHouse($this->idHouse);
        return $this->house;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getRate(): float {
        return $this->rate;
    }

    public function getLikes(): int {
        return $this->likes;
    }

    public function getDislikes(): int {
        return $this->dislikes;
    }

    public function getReporting(): array {
        if (!isset($this->reporting)) {
            $this->reporting = Report::readAllReport($this->id);
        }
        return $this->reporting;
    }

    public function getNbReporting(): int {
        return count($this->getReporting());
    }

    public function getCreationDate(): string {
        return DAO::get()->quickExecute('SELECT creation_date FROM Comment WHERE idcom = :id', [$this->getId()])[0]['creation_date'];
    }

    //-----Setters-----

    public function setContent(string $content): void {
        $this->content = $content;
        $this->edit = true;
        $this->updateComment();
    }

    public function setRate(float $rate): void {
        $this->rate = $rate;
        $this->edit = true;
        $this->updateComment();
    }

    //-----Autre-----

    /**
     * Informe si le commentaire a été modifié
     * @return bool
     */
    public function isEdited(): bool {
        return $this->edit;
    }

    /**
     * Incrémente le nombre de mentions "j'aime" dans la base de données **et** sur ce `Comment`
     * @return void
     */
    public function addLike(): void {
        $this->likes++;
        $this->updateComment();
    }

    /**
     * Décrémente le nombre de mentions "j'aime" dans la base de données **et** sur ce `Comment`
     * @return void
     */
    public function removeLike(): void {
        // On considère qu'un commentaire ne peut pas avoir 0 mention "j'aime"
        if ($this->likes > 0)
            $this->likes--;
        $this->updateComment();
    }

    /**
     * Incrémente le nombre de mentions "je n'aime pas" dans la base de données **et** sur ce `Comment`
     * @return void
     */
    public function addDislike(): void {
        $this->dislikes++;
        $this->updateComment();
    }

    /**
     * Décrémente le nombre de mentions "je n'aime pas" dans la base de données **et** sur ce `Comment`
     * @return void
     */
    public function removeDislike(): void {
        // On considère qu'un commentaire ne peut pas avoir 0 mention "je n'aime pas"
        if ($this->dislikes > 0)
            $this->dislikes--;
        $this->updateComment();
    }

    /**
     * Ajoute un signalements dans la base de données 'Report' **et** sur ce `Comment`
     * @param User|int $flagger
     * @param string $categorie
     * @param string $content
     * @return void
     */
    public function addReporting(User|int $flagger, string $categorie, string $content = ""): void {
        $this->reporting[] = Report::createReport($this->id, $flagger, $categorie, $content);
    }

    /**
     * Renvoie l'id le plus grand de la base de données pour la relation `Comment`,
     * `null` si la relation est vide
     * @return int|null
     */
    public static function getLastId(): ?int {
        return DAO::get()->quickExecute(
            'SELECT max(idCom) AS nb FROM Comment'
        )[0]['nb'];
    }

    /**
     * Renvoie une chaîne de caractères représentant la note du commentaire.
     * Celle-ci est formatée avec 5 caractères ★ ou ☆ selon la note.
     * @return string
     */
    public function getStars(): string {
        return starFormat($this->getRate());
    }

    /**
     * Insère une nouvelle entrée dans la base de donnée, et renvoie son équivalent objet `Comment`
     * @param string $content
     * @param float $rate
     * @param int $like
     * @param int $dislike
     * @param User|int $owner Objet, ou identifiant de l'utilisateur dans la base de données
     * @param House|int $house Objet, ou identifiant de la maison dans la base de données
     * @return Comment
     */
    public static function createComment(string $content, float $rate, int $like, int $dislike, User|int $owner, House|int $house): Comment {
        DAO::get()->quickExecute('
            INSERT INTO Comment (idOwner,
                                 idHouse,
                                 content,
                                 rate,
                                 "like",
                                 dislike)
            VALUES (:idowner,
                    :idhouse,
                    :content,
                    :rate,
                    :like,
                    :dislike)',
            [
                ':idowner' => gettype($owner) == 'object' ? $owner->getId() : $owner,
                ':idhouse' => gettype($house) == 'object' ? $house->getId() : $house,
                ':content' => $content,
                ':rate' => $rate,
                ':like' => $like,
                ':dislike' => $dislike
            ]
        );
        // Je vais considérer qu'avec getLastId on obtient l'id de la ligne qu'on vient d'insérer.
        // Si c'est pas le cas, je vois pas trop comment faire...
        return self::readComment(self::getLastId());
    }

    /**
     * Retourne un objet `Comment` à partir de son identifiant dans la base de données
     * @param $id
     * @return Comment|null
     */
    public static function readComment($id): ?Comment {
        $row = DAO::get()->quickExecute(
            'SELECT * FROM Comment WHERE idCom = :id',
            [":id" => $id]
        );

        if (count($row) == 0) {
            error_log('Un commentaire n\'a pas pu être lu, car il semble ne pas exister.');
            throw new RuntimeException("Le commentaire d'identifiant n°$id ne semble pas exister.");
        }

        if (count($row) > 1)
            error_log('Un commentaire lu existe en plusieurs exemplaires ! Il y a une incohérence dans la BD.');

        $row = $row[0];
        // Créer un objet Comment à partir de la ligne récupérée
        return new Comment(
            $id,
            $row['idowner'],
            $row['idhouse'],
            $row['content'],
            $row['rate'],
            $row['like'],
            $row['dislike'],
            $row['edit']
        );

    }

    /**
     * Retourne une liste contenant tous les commentaires de la base de données (`Comment`).
     * @return array
     * @throws Exception
     */
    public static function readAllComments(): array {
        $query = DAO::get()->quickExecute('SELECT idCom FROM Comment Order By creation_date DESC');
        $result = [];
        foreach ($query as $row)
            $result[] = Comment::readComment($row['idcom']);
        return $result;
    }

    /**
     * Mets à jour le commentaire correspondant dans la base de données.
     * Cela inclus le contenu, la note, le nombre de signalements, de like et de dislike et si le commentaire a été modifiéqq.
     * @return void
     */
    public function updateComment(): void {
        DAO::get()->quickExecute('            
            UPDATE Comment
            SET content   = :content,
                rate      = :rate,
                "like"    = :like,
                dislike   = :dislike,
                edit      = :edit
            WHERE idcom = :id',
            [
                ":content" => $this->getContent(),
                ":rate" => $this->getRate(),
                ":like" => $this->getLikes(),
                ":dislike" => $this->getDislikes(),
                ":edit" => $this->edit,
                ":id" => $this->getId()
            ]
        );
    }

    /**
     * Supprime ce `Comment` (base de données uniquement)
     * @return void
     */
    public function deleteComment(): void {
        DAO::get()->quickExecute('
            DELETE
            FROM notification
            where reportComment = :id',
            [':id'=> $this->getId()]);
        DAO::get()->quickExecute('
            DELETE
            FROM report
            where idComment = :id',
            [':id'=> $this->getId()]);
        DAO::get()->quickExecute('            
            DELETE
            FROM Comment
            WHERE idCom = :id',
            [':id' => $this->getId()]
        );
    }
}

