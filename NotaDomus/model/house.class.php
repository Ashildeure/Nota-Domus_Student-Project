<?php
//-----Imports-----

require_once __DIR__ . '/DAO.class.php';
require_once __DIR__ . '/location.class.php';
require_once __DIR__ . '/outstanding.class.php';
require_once __DIR__ . '/comment.class.php';

/**
 * La classe `House` représente une maison d'illustre.<br>
 * **`RUD`** (create absent car la création d'une maison est une situation exceptionnelle
 * qui se fait vraisemblablement à la main)
 */
class House {
    //-----Attributs-----

    /**
     * @var int L'identifiant unique de la maison d'illustre
     */
    private int $id;

    /**
     * @var string Le nom de la maison d'illustre
     */
    private string $name;

    /**
     * @var string Une description de la maison d'illustre
     */
    private string $presentation;

    /**
     * @var int La date à laquelle le bâtiment a reçu le label "Maison d'Illustre"
     */
    private int $labelDate;

    /**
     * @var array|null Liste de liens affiliés à cette maison
     */
    private ?array $links = [];

    /**
     * @var Location Objet Location associé à la maison (son emplacement sous forme d'objet)
     */
    private Location $location;

    /**
     * @var int Identifiant dans la base de données de l'illustre associé
     */
    private int $idOutstanding;

    /**
     * @var Outstanding|null Objet `Outstanding` correspondant à cette maison
     */
    private ?Outstanding $outstanding;

    /**
     * @var array|null Liste des commentaires postés sous la Maison d'Illustre
     */
    private ?array $comments;

    //-----Constructeur-----

    public function __construct(int $id, string $name, string $presentation, int $labelDate) {
        $this->id = $id;
        $this->setName($name);
        $this->setPresentation($presentation);
        $this->setLabelDate($labelDate);
        $this->readLinks();
        $this->idOutstanding = $this->readIdOutstanding();
        $this->comments = $this->readIdComments();
    }

    //-----Getters-----

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPresentation(): string {
        return $this->presentation;
    }

    public function getLabelDate(): int {
        return $this->labelDate;
    }

    public function getLinks(): array {
        return $this->links;
    }

    public function getLocation(): Location {
        if (!isset($this->location))
            $this->location = Location::readLocation($this->id);
        return $this->location;
    }

    /**
     * @throws Exception
     */
    public function getOutstanding(): Outstanding {
        if (!isset($this->outstanding))
            $this->outstanding = Outstanding::readOutstanding($this->idOutstanding);
        return $this->outstanding;
    }

    /**
     * @throws Exception
     */
    public function getComments(): array {
        $liste = [];
        foreach ($this->comments as $comment)
            $liste[] = Comment::readComment($comment);
        return $liste;
    }

    //-----Setters-----

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setPresentation(string $presentation): void {
        $this->presentation = $presentation;
        $this->updateHouse($this->id, $this->name, $this->presentation);
    }

    public function setLabelDate(int $labelDate): void {
        $this->labelDate = $labelDate;
    }

    public function setLinks(string $type, string $link): void {
        $this->links[$type] = $link;
    }

    //-----Autre-----

    /**
     * Retourne les attributs de la maison sous forme d'un `array` contenant, dans l'ordre suivant :
     * - `name`,
     * - `presentation`,
     * - `avgRate`,
     * - `id`,
     * - `labelDate`,
     * - `links[]` (tableau de liens),
     * - `comments[]` (tableau de commentaires)
     * @return array
     * @throws Exception
     */
    public function getInfo(): array {
        return [
            $this->getName(),
            $this->getPresentation(),
            $this->getAvgRate(),
            $this->getId(),
            $this->getLabelDate(),
            $this->getLinks(),
            $this->getComments()
        ];
    }

    /**
     * Retourne une sélection d'attributs de la maison sous forme d'un `array` contenant, dans l'ordre suivant :
     * - `name`,
     * - `presentation`,
     * - `avgRate`,
     * - `id`
     * @return array
     * @throws Exception
     */
    public function getInfoRecap(): array {
        return [
            $this->getName(),
            $this->getPresentation(),
            $this->getAvgRate(),
            $this->getId()
        ];
    }

    /**
     * Renvoie la note moyenne donnée à la maison par les commentaires
     * @return float
     * @throws Exception
     */
    public function getAvgRate(): float {
        $comments = $this->getComments();
        $nbComments = 0;
        $noteTotale = 0;
        foreach ($comments as $comment) {
            $nbComments += 1;
            $noteTotale += $comment->getRate();
        }

        return $nbComments == 0
            ? 0
            : $noteTotale / $nbComments;
    }

    /**
     * Renvoie une chaîne de caractères représentant la note moyenne de la maison.
     * Celle-ci est formatée avec 5 caractères ★ ou ☆ selon la note.
     * @return string
     * @throws Exception
     */
    public function getStars(): string {
        return starFormat($this->getAvgRate());
    }

    /**
     * Récupère tous les commentaires de la maison, sous forme d'un `array` contenant des `Comment`.
     * @return array
     */
    private function readIdComments(): array {
        $table = DAO::get()->quickExecute('            
            SELECT idCom
            FROM Comment
            WHERE idHouse = :id',
            ['id' => $this->id]
        );
        $comments = [];
        foreach ($table as $comment)
            $comments[] = $comment['idcom'];
        return $comments;
    }

    /**
     * Lit dans la bd les liens de la maison
     * @return void
     */
    private function readLinks(): void {
        $ligne = DAO::get()->quickExecute('            
            SELECT websiteLink, instaLink, facebookLink, twitterLink
            FROM House
            WHERE idHouse = :id',
            [':id' => $this->id]
        )[0];

        // gestion des liens
        if (isset($ligne['websitelink']))
            $this->setLinks('website', $ligne['websitelink']);

        if (isset($ligne['instalink']))
            $this->setLinks('insta', $ligne['instalink']);

        if (isset($ligne['facebooklink']))
            $this->setLinks('facebook', $ligne['facebooklink']);

        if (isset($ligne['twitterlink']))
            $this->setLinks('twitter', $ligne['twitterlink']);
    }

    /**
     * Récupère l'identifiant correspondant à cette maison.
     * @return int
     */
    private function readIdOutstanding(): int {
        $table = DAO::get()->quickExecute('            
            SELECT o.idOutstanding AS idOutstanding
            FROM Outstanding o,
                 House h
            WHERE idHouse = :id
              AND o.idOutstanding = h.idOutstanding',
            [':id' => $this->id]
        )[0];
        return $table['idoutstanding'];
    }

    /**
     * Récupère un objet `House` à partir de son identifiant dans la base de données.
     * @param int $id
     * @return House
     */
    public static function readHouse(int $id): House {
        $ligne = DAO::get()->quickExecute('            
            SELECT houseName, presentation, labelDate
            FROM House
            WHERE idHouse = :id',
            [':id' => $id]
        );

        if (count($ligne) == 0) {
            $message = "Maison $id non trouvée dans la base de données";
            error_log($message);
            throw new RuntimeException($message);
        }

        if (count($ligne) > 1)
            error_log("La maison $id existe en plusieurs exemplaires ! Il y a une incohérence dans la BD.", LOG_WARNING);

        $ligne = $ligne[0];

        if (!isset($ligne['labeldate']))
            $ligne['labeldate'] = 0;

        return new House(
            $id,
            $ligne['housename'],
            $ligne['presentation'],
            $ligne['labeldate']
        );
    }

    /**
     * Trouve les maisons correspondant à plusieurs filtres donnés
     * @param array $epoque [dateDebut, dateFin] Période historique à laquelle appartient la maison.
     * @param array $regions Liste des régions à filtrer.
     * @param array $departments Liste des départements à filtrer.
     * @param array $etoiles Liste des notes (étoiles) pour filtrer les maisons.
     * @param bool $favorite Si `true`, sélectionne uniquement les maisons favorites de l'utilisateur.
     * @param bool $friend
     * @param int $idUser
     * @return array Liste des maisons correspondant aux filtres.
     */
    public static function readFilters(array $epoque, array $regions, array $departments, array $etoiles, bool $favorite, bool $friend, int $idUser): array {
        // Début de la construction de la requête SQL.
        $query = 'SELECT h.idHouse FROM House h';
        $params = []; // Stockage des paramètres pour la requête préparée.
        $joinClauses = []; // Liste des jointures SQL nécessaires.
        $whereClauses = []; // Liste des conditions WHERE pour filtrer.

        // --- Filtre : Époque de la maison ---
        if (!empty($epoque)) {
            // Ajout d'une jointure avec la table OUTSTANDING pour accéder à l'époque.
            $joinClauses[] = 'INNER JOIN Outstanding o ON o.idOutstanding = h.idOutstanding';
            $whereClauses[] = 'o.epoque BETWEEN :min AND :max';
            $params[':min'] = $epoque[0]; // Date de début.
            $params[':max'] = $epoque[1]; // Date de fin.
        }

        // --- Filtre : Régions ---
        if (!empty($regions)) {
            // Ajout d'une jointure avec la table REGION pour filtrer par région.
            $joinClauses[] = 'INNER JOIN REGION r ON r.idRegion = h.idRegion';
            // Création d'une clause IN dynamique pour les régions.
            $regionClause[] = self::buildInClause('r.regionName', $regions, 'region', $params);
        }

        // --- Filtre : Départements ---
        if (!empty($departments)) {
            // Ajout d'une jointure avec la table DEPARTMENT pour filtrer par département.
            $joinClauses[] = 'INNER JOIN Department d ON d.idDepartment = h.idDepartment';
            // Création d'une clause IN dynamique pour les départements.
            $departmentClause[] = self::buildInClause('d.departmentName', $departments, 'department', $params);
        }

        // Combiner les clauses de régions et de départements avec OR
        if (!empty($regionClause) || (!empty($departmentClause))) {
            // Convertir les clauses en chaînes si elles sont des tableaux
            $regionClause = isset($regionClause) ? (is_array($regionClause) ? implode(' OR ', $regionClause) : $regionClause) : [];
            $departmentClause = isset($departmentClause) ? (is_array($departmentClause) ? implode(' OR ', $departmentClause) : $departmentClause) : [];

            // Ajoute la condition avec OR entre régions et départements
            $whereClauses[] = '(' . implode(' OR ', array_filter([$regionClause, $departmentClause])) . ')';
        }

        // --- Filtre : Favoris de l'utilisateur ---
        if ($favorite) {
            // Ajout d'une jointure avec la table FAVORITE pour sélectionner les favoris de l'utilisateur.
            $joinClauses[] = 'INNER JOIN FAVORITE f ON f.idHouse = h.idHouse';
            $whereClauses[] = 'f.idowner = :user'; // Filtrer par ID utilisateur.
            $params[':user'] = $idUser;
        }

        // --- Construction de la requête finale ---
        // Ajout des jointures (si nécessaires).
        $query .= ' ' . implode(' ', $joinClauses);

        // Ajout des conditions WHERE (si présentes).
        if (!empty($whereClauses)) {
            $query .= ' WHERE ' . implode(' AND ', $whereClauses);
        } else {
            // selection si aucun filtre est actif
            $query .= 'LIMIT 30';
        }
        // --- Exécution de la requête ---
        try {
            // Exécute la requête SQL et récupère les résultats.
            $table = DAO::get()->quickExecute($query, $params);

            // Création d'une liste de maisons à partir des résultats.
            $houses = [];
            foreach ($table as $house) {
                $tmp = self::readHouse($house['idhouse']);
                // --- Filtre : Nombre d'étoiles ---
                if (empty($etoiles) || in_array($tmp->getAvgRate(), $etoiles))
                    $houses[] = $tmp;
            }
            // --- Filtre : Nombre d'étoiles ---

            return $houses;

        } catch (Exception $e) {
            // En cas d'erreur, lève une exception avec un message explicatif.
            $message = 'Erreur lors de l\'exécution de `House::readFilter`  : ' . $e->getMessage();
            error_log($message);
            throw new RuntimeException($message);
        }
    }

    /**
     * Génère une clause SQL IN avec des placeholders et ajoute les paramètres associés.
     * @param string $fieldName Nom de la colonne à utiliser pour la clause IN.
     * @param array $values Liste des valeurs à inclure.
     * @param string $prefix Préfixe utilisé pour générer les noms des placeholders.
     * @param array &$params Référence au tableau des paramètres pour la requête préparée.
     * @return string La clause IN générée.
     */
    private static function buildInClause(string $fieldName, array $values, string $prefix, array &$params): string {
        $placeholders = []; // Liste des placeholders pour la clause IN.
        foreach ($values as $index => $value) {
            $placeholder = ":{$prefix}_$index";
            $placeholders[] = $placeholder;
            $params[$placeholder] = $value; // Ajout de la valeur associée au placeholder.
        }
        // Retourne la clause IN construite.
        return "$fieldName IN (" . implode(', ', $placeholders) . ")";
        // TODO: ATTENTION FAILLE D'INJECTION PROBABLE - il doit y avoir un moyen de faire ça sans interpolation ni concaténation directe
    }

    /**
     *Permet de read des house coherente avec la recherche
     * @param string $research debut d'un nom de maison,d'un outstanding ou d'une oeuvre
     * @return array de house
     * @throws Exception
     */
    public static function readLike(string $research): array {
        $dao = DAO::get();
        $query = $dao->prepare('            
            SELECT DISTINCT h.idHouse
            FROM House h
                    NATURAL JOIN Outstanding o
                    NATURAL JOIN Work w
                    NATURAL JOIN department d
                    NATURAL JOIN region r
            WHERE unaccent(h.houseName) ILIKE unaccent(:research)
                OR unaccent(o.outstandingName) ILIKE unaccent(:research)
                OR unaccent(w.workName) ILIKE unaccent(:research)
                OR unaccent(r.regionname) ILIKE unaccent(:research)
                OR unaccent(d.departmentname) ILIKE unaccent(:research)
                OR unaccent(h.address) ILIKE unaccent(:research)
                OR unaccent(h.city) ILIKE unaccent(:research)
        ');
        $query->execute([":research" => "%$research%"]);
        $table = $query->fetchAll();

        // Création d'une liste de maisons à partir des résultats.
        $houses = [];
        foreach ($table as $house)
            $houses[] = self::readHouse($house['idhouse']);
        return $houses;
    }

    /**
     * Dans la base de données, met à jour :
     * - le nom de la maison d'illustre selon `$newName`
     * - sa description selon `$newPresentation`
     * @param int $id L'identifiant de la maison à mettre à jour
     * @param string $newName
     * @param string $newPresentation
     * @return void
     */
    public static function updateHouse(int $id, string $newName, string $newPresentation): void {
        DAO::get()->quickExecute('            
            UPDATE House
            SET houseName    = :name,
                presentation = :presentation
            WHERE idHouse = :id',
            [
                ':name' => $newName,
                ':presentation' => $newPresentation,
                ':id' => $id
            ]
        );
    }

    /**
     * Supprime cette maison ainsi que tous ses commentaires de la base de données
     * @return void
     */
    public function deleteHouse(): void {
        $dao = DAO::get();

        $dao->quickExecute('
            DELETE
            FROM House
            WHERE idHouse = :id',
            [":id" => $this->getId()]
        );

        $dao->quickExecute('            
            DELETE
            FROM Comment
            WHERE idHouse = :id',
            [":id" => $this->getId()]
        );
    }
}