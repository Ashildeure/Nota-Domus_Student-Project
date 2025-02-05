<?php
//-----Imports-----

require_once __DIR__ . '/DAO.class.php';
require_once __DIR__ . '/house.class.php';

/**
 * Classe représentant un illustre.<br>
 * **`R`** (La table des illustres existante est supposée complète, cohérente et correcte)
 */
class Outstanding {
    //-----Attributs-----

    /**
     * @var int Identifiant unique de l'illustre (tel que dans la base de données)
     */
    private int $id;

    /**
     * @var string Nom de l'illustre
     */
    private string $name;

    /**
     * TODO: Les données ne sont pas stockées dans la BD ! Pose des problèmes pour le read
     * @var string "Catégorie" de l'illustre
     */
    private string $type;

    /**
     * @var int siecle auquel a vecu l'illustre
     */
    private int $epoque;

    /**
     * @var array Liste des oeuvres de cet illustre
     */
    private array $works = [];

    /**
     * @var array Liste des id des maisons  associées à cet illustre
     */
    private array $idHouses;

    /**
     * @var array Liste des maisons (objets `House`) associées à cet illustre
     */
    private array $houses;

    /**
     * @var array Permet à `getRomanNumber` de renvoyer les chiffres romains correspondant
     */
    private const ROMAN_TABLE = [
        14 => "XIV",
        15 => "XV",
        16 => "XVI",
        17 => "XVII",
        18 => "XVIII",
        19 => "XIX",
        20 => "XX",
        21 => "XXI"
    ];

    //-----Constructeur-----

    /**
     * @throws Exception
     */
    public function __construct(int $id, string $name, string $type, int $epoque) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->epoque = $epoque;
        $this->readIdHouses();
        $this->readWorks();
    }

    //-----Getters-----

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getEpoque(): int {
        return $this->epoque;
    }

    public function getWorks(): array {
        return $this->works;
    }

    public function getHouses(): array {
        if (!isset($this->houses)) {
            $this->readIdHouses();
            foreach ($this->idHouses as $house)
                $this->houses[] = House::readHouse($house);
        }
        return $this->houses;
    }

    //-----Autre-----

    /**
     * Transforme l'époque de l'illustre en chiffre romain (ex: 19 devient XIX)
     * @return string
     */
    public function getRomanNumber(): string {
        return self::ROMAN_TABLE[$this->getEpoque()];
    }


    /**
     * Récupère la liste des maisons associées à cet illustre et mets à jour `$houses` avec le résultat
     * @return void
     */
    private function readIdHouses(): void {
        $table = DAO::get()->quickExecute(
            'SELECT idHouse FROM House WHERE idOutstanding = :id',
            [":id" => $this->id]
        );
        // Vider la liste
        $this->idHouses = [];
        // La remplir à nouveau avec les nouvelles données
        foreach ($table as $house) {
            $this->idHouses[] = $house['idhouse'];
        }
    }

    /**
     * Lit la liste d'oeuvre d'un illustre
     * @return void
     */
    private function readWorks(): void {
        $table = DAO::get()->quickExecute(
            'SELECT workName FROM Work WHERE idOutstanding = :id',
            [":id" => $this->id]
        );
        $this->works = [];
        foreach ($table as $work)
            $this->works[] = $work["workname"];
    }

    /**
     * Renvoie un objet `Outstanding` à partir de son identifiant dans la base de données
     * @param int $id
     * @return Outstanding
     * @throws Exception
     */
    public static function readOutstanding(int $id): Outstanding {
        $ligne = DAO::get()->quickExecute('
            SELECT *
            FROM Outstanding
            WHERE idOutstanding = :id',
            [":id" => $id]
        )[0];
        return new Outstanding(
            $id,
            $ligne['outstandingname'],
            $ligne['type'],
            $ligne['epoque']
        );
    }
}