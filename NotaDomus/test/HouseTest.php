<?php

require_once __DIR__ . '/../model/house.class.php';
require_once __DIR__ . '/../model/DAO.class.php';
require_once __DIR__ . '/util.php';


use PHPUnit\Framework\TestCase;

class HouseTest extends TestCase {

    public static ?House $houseNumber1;
    public static ?House $houseNumber2;

    /**
     * @throws Exception
     */
    protected function setUp(): void {
        global $databaseName;
        $databaseName = 'notadomus_test';
        parent::setUp();
        self::$houseNumber1 = new House(
            1,
            'Musée Emile Guillaumin',
            'Émile Guillaumin (1873-1951) est un paysan et écrivain. Il travaille la terre depuis son plus jeune âge et écrit ses premiers poèmes à l\'âge de 17 ans, dans le patois local du Bourbonnais. En 1892, il emménage dans l\'exploitation dite «Les Vignes», à Ygrande. C\'est ici qu\'en 1901, marqué par la lecture de "Jacquou le Croquant" d\'Eugène Le Roy, il entreprend l\'écriture de "La vie d\'un simple", son chef-d\'½uvre décrivant la condition paysanne au XIXe siècle. Malgré le succès littéraire, Emile Guillaumin n\'abandonne pas le monde paysan et contribue même à la formation d\'un syndicalisme agraire. Dans sa maison devenue musée, on découvre désormais des témoignages de la vie paysanne de l\'époque, de l\'activisme de son illustre occupant, ainsi que de sa carrière littéraire.. propriete /gestion : Propriété de la commune d\'Ygrande.',
            2012
        );

        self::$houseNumber2 = new House(
            19,
            'Maison-atelier Emile Boggio',
            'Peintre et photographe, Émile Boggio (1857-1920) né à Caracas, fut l\'élève de Jean-Paul Laurens à l?Académie Julian , sa peinture est de style symboliste. Il obtient la médaille d?argent à l?Exposition universelle de Paris en 1900 pour son ½uvre "Labor". À cette période, sa peinture prend un tournant impressionniste. Sa dernière exposition à Caracas, en 1919, laissera une grande influence sur la peinture vénézuélienne. Un musée monographique, inauguré en 1973 à Caracas, en témoigne. Après un voyage de trois ans en Italie avec son ami, le peintre Henri Martin, il s?installe à Auvers-sur-Oise en 1910, dans un corps de ferme transformé en maison-atelier où il peindra plus de quatre cents tableaux. Il décède le 7 juin 1920 à l?âge de soixante-trois ans. Sa dernière peinture, un pommier en fleur, est toujours sur son chevalet.. propriete /gestion : Propriété privée.',
            2017
        );
    }

    protected function tearDown(): void {
        parent::tearDown();
        self::$houseNumber1 = null;
        self::$houseNumber2 = null;
    }

    /**
     * @throws Exception
     */
    public function testGetOutstanding() {
        self::assertNotNull(self::$houseNumber1->getOutstanding());
        self::assertNotNull(self::$houseNumber2->getOutstanding());
    }

    /**
     * Test du CRUD de `House`
     */
    public function testHouseDB() {
        try {
            $house = new House(10,
                "tour moncade",
                "gaston f�bus (1331-1391) est un seigneur f�odal, �crivain et po�te. entour� d?une cour fastueuse, le comte de foix ach�ve les fortifications d?un ch�teau dont la construction a d�but� en 1242 et dont il ne reste aujourd'hui que le donjon. dans son \"livre de chasse\", qui reste une r�f�rence jusqu?au xixe si�cle, gaston iii, consid�r� comme l?un des meilleurs chasseurs de son �poque, d�taille les composantes de cet univers essentiel dans le monde m�di�val. la tour moncade, dernier vestige du ch�teau natal de gaston f�bus revendu pour partie � des d�molisseurs au lendemain de la r�volution, pr�sente des am�nagements int�rieurs et ext�rieurs destin�s � faire d�couvrir son histoire. propri�t� de la ville d'orthez.",
                2012);

            $verif = house::readHouse(10);
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        $this->assertEqualsIgnoringCase($house->getName(), $verif->getName());
    }


    public function testReadLinks() {
        $house = new House(10,
            "tour moncade",
            "gaston f�bus (1331-1391) est un seigneur f�odal, �crivain et po�te. entour� d?une cour fastueuse, le comte de foix ach�ve les fortifications d?un ch�teau dont la construction a d�but� en 1242 et dont il ne reste aujourd'hui que le donjon. dans son \"livre de chasse\", qui reste une r�f�rence jusqu?au xixe si�cle, gaston iii, consid�r� comme l?un des meilleurs chasseurs de son �poque, d�taille les composantes de cet univers essentiel dans le monde m�di�val. la tour moncade, dernier vestige du ch�teau natal de gaston f�bus revendu pour partie � des d�molisseurs au lendemain de la r�volution, pr�sente des am�nagements int�rieurs et ext�rieurs destin�s � faire d�couvrir son histoire. propri�t� de la ville d'orthez.",
            2012);
        $verif = "https://www.orthezanimation.com/chateau-moncade-1";
        $this->assertTrue(in_array($verif, $house->getlinks()));
    }

    public function testGetComments() {
        //--PreparationDB
        DAO::get()->quickExecute('
            INSERT INTO Comment (idCom, idOwner, idHouse, content, rate, reporting, "like", dislike)
            VALUES (100, 100, 22, \'content\', 5, 0, 0, 0),
                   (101, 100, 22, \'content\', 5, 0, 0, 0)'
        );

        $house = House::readHouse(22);
        $comments = $house->getComments();
        $this->assertEquals(2, count($comments));

        //--Clean DB
        DAO::get()->quickExecute('
            DELETE FROM Comment
                   WHERE idCom = 100
                      OR idCom = 101'
        );

    }

    public function testGetAvgRate() {
        try {
            //--PreparationDB
            DAO::get()->quickExecute('
                INSERT INTO Comment (idcom, idowner, idhouse, content, rate, reporting, "like", dislike)
                VALUES (100, 100, 22, \'content\', 5, 0, 0, 0),
                       (101, 100, 22, \'content\', 5, 0, 0, 0)');

            $house = House::readHouse(22);
            $house->getComments();

            $this->assertEquals(5, $house->getAvgRate());

            //--Clean DB
            DAO::get()->quickExecute('DELETE FROM Comment WHERE idcom = 100 OR idcom = 101');
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
    }

    public function testGetInfo() {
        try {
            //attendu
            $expected = [
                self::$houseNumber1->getName(),
                self::$houseNumber1->getPresentation(),
                self::$houseNumber1->getAvgRate(),
                self::$houseNumber1->getId(),
                self::$houseNumber1->getLabelDate(),
                self::$houseNumber1->getLinks(),
                self::$houseNumber1->getComments()
            ];

            $this->assertEquals($expected, self::$houseNumber1->getInfo());
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
    }

    public function testGetInfoRecap() {
        //attendu
        $expected = [
            self::$houseNumber1->getName(),
            self::$houseNumber1->getPresentation(),
            self::$houseNumber1->getAvgRate(),
            self::$houseNumber1->getId()
        ];
        $this->assertEquals($expected, self::$houseNumber1->getInfoRecap());
    }

    public function testReadFilters() {
        try {
            // Données simulées
            $epoque = [17, 19];
            $regions = [];
            $departments = [];
            $etoiles = [];
            $favorite = true;
            $friend = false;
            $idUser = 100;

            // Resultat attendu
            $expectedResult = 5; // il y a ce nombre de maison qui correspond a ces critéres

            // Appeler la fonction
            $result = House::readFilters($epoque, $regions, $departments, $etoiles, $favorite, $friend, $idUser);

        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }//SELECT h.idHouse FROM House h INNER JOIN Outstanding o ON o.idOutstanding = h.idOutstanding INNER JOIN FAVORITE f ON f.idHouse = h.idHouse WHERE o.epoque BETWEEN 17 AND 19 AND f.idowner = 100;
        // Vérifier les résultats
        $this->assertCount(
            $expectedResult, // Résultat attendu
            $result
        );
    }

    /**
     * @throws Exception
     */
    public function testReadWithoutFilter() {
        // Données simulées
        $epoque = [];
        $regions = [];
        $departments = [];
        $etoiles = [];
        $favorite = false;
        $friend = false;
        $idUser = 100;

        // Resultat attendu
        $expectedResult = 30;

        // Appeler la fonction
        $result = House::readFilters($epoque, $regions, $departments, $etoiles, $favorite, $friend, $idUser);

        // Vérifier les résultats
        $this->assertCount(
            $expectedResult, // Résultat attendu
            $result
        );
    }

    /**
     * @throws Exception
     */
    public function testReadLike() {
        $houses1 = House::readLike("marie");
        $this->assertEquals(9, sizeof($houses1));
        $houses2 = House::readLike("atelier");
        $this->assertEquals(12, sizeof($houses2));
        $house3 = House::readLike("liberté");
        $this->assertEquals(3, sizeof($house3));
        $house4 = House::readLike("château");
        $this->assertEquals(34, sizeof($house4));
        $house5 = House::readLike("chateau");
        $this->assertEquals(34, sizeof($house5));
    }

}
