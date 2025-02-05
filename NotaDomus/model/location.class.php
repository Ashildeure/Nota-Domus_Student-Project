<?php
//-----Imports-----

require_once __DIR__ . '/DAO.class.php';

/**
 * `Location` représente un emplacement géographique.<br>
 * **`R`** uniquement (Toutes les régions sont supposées répertoriées : on n'en crée, modifie, supprime pas...)
 */
class Location {
    //-----Attributs-----

    /**
     * @var int L'identifiant de l'emplacement
     */
    private int $id;

    /**
     * @var string Le nom de la ville de l'emplacement
     */
    private string $city;

    /**
     * @var int Le code postal de la ville de l'emplacement
     */
    private int $postalCode;

    /**
     * @var string Le nom de la région de l'emplacement
     */
    private string $regionName;

    /**
     * @var string Le nom du département de l'emplacement
     */
    private string $departmentName;

    /**
     * @var int Le code de la région de l'emplacement
     */
    private int $regionCode;

    /**
     * @var int Le code de département de l'emplacement
     */
    private int $departmentCode;

    /**
     * @var string L'adresse complète de l'emplacement, sous forme de texte
     */
    private string $address;

    // TODO: À VOIR PLUS TARD POUR CHANGER LE TYPE
    /**
     * @var string Latitude (1ère coordonnée géographique) de l'emplacement
     */
    private string $latitude;

    /**
     * @var string Longitude (2ème coordonnée géographique) de l'emplacement
     */
    private string $longitude;

    //-----Constructeur-----

    public function __construct(int $idHouse, string $city, int $postalCode, string $regionName, string $departmentName, int $regionCode, int $departmentCode, string $address, string $latitude, string $longitude) {
        $this->id = $idHouse;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->regionName = $regionName;
        $this->departmentName = $departmentName;
        $this->regionCode = $regionCode;
        $this->departmentCode = $departmentCode;
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    //-----Getters-----

    public function getId(): int {
        return $this->id;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function getPostalCode(): int {
        return $this->postalCode;
    }

    public function getRegionName(): string {
        return $this->regionName;
    }

    public function getDepartmentName(): string {
        return $this->departmentName;
    }

    public function getRegionCode(): int {
        return $this->regionCode;
    }

    public function getDepartmentCode(): int {
        return $this->departmentCode;
    }

    public function getAddresse(): string {
        return $this->address;
    }

    public function getLatitude(): string {
        return $this->latitude;
    }

    public function getLongitude(): string {
        return $this->longitude;
    }

    //-----Autre-----

    /**
     * Retourne une chaîne de caractères représentant les coordonnées (latitude et longitude), séparées par un espace
     * @return string
     */
    public function getGeoCoordinates(): string {
        return $this->latitude . ' ' . $this->longitude;
    }

    /**
     * Retourne un objet `Location` à partir de l'identifiant d'une maison d'illustre dans la base
     * @param int $id
     * @return Location
     */
    public static function readLocation(int $id): Location {
        $ligneHouse = DAO::get()->quickExecute('            
            SELECT city,
                   postalCode,
                   regionName,
                   departmentName,
                   h.idRegion,
                   h.idDepartment,
                   address,
                   latitude,
                   longitude
            FROM House h,
                 Region r,
                 Department d
            WHERE idHouse = :id
              AND h.idRegion = r.idRegion
              AND h.idDepartment = d.idDepartment',
            [":id" => $id]
        )[0];

        return new Location(
            $id,
            $ligneHouse['city'],
            $ligneHouse['postalcode'],
            $ligneHouse['regionname'],
            $ligneHouse['departmentname'],
            $ligneHouse['idregion'],
            $ligneHouse['iddepartment'],
            $ligneHouse['address'],
            $ligneHouse['latitude'],
            $ligneHouse['longitude']
        );
    }

    /**
     * TODO: Compléter la docstring
     * @return array
     */
    public static function readRegions(): array {
        $table = DAO::get()->quickExecute('SELECT regionName FROM Region ORDER BY regionName');
        $regions = [];
        foreach ($table as $region)
            $regions[] = $region['regionname'];
        return $regions;
    }

    /**
     * TODO: Compléter la docstring
     * @return array
     */
    public static function readDepartments(): array {
        $table = DAO::get()->quickExecute('SELECT departmentName FROM Department ORDER BY departmentName');
        $departments = [];
        foreach ($table as $department)
            $departments[] = $department['departmentname'];
        return $departments;
    }

}