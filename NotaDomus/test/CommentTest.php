<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../model/comment.class.php';

class CommentTest extends TestCase {

    public static ?Comment $commentNumber1;
    public static ?Comment $commentNumber2;
    public static ?House $houseNumber1;
    public static ?House $houseNumber2;
    public static ?User $userNumber1;
    public static ?User $userNumber2;

    protected function setUp(): void {
        global $databaseName;
        $databaseName = 'notadomus_test';
        try {
            parent::setUp();
            self::$commentNumber1 = new Comment(id: 1, idOwner: 6, idHouse: 1, content: 'bof, un oiseau a failli me manger', rate: 3.5, likes: 0, dislikes: 0, edit: false);
            self::$commentNumber2 = new Comment(id: 1, idOwner: 100, idHouse: 175, content: 'j\'aime les chips', rate: 5.0, likes: 0, dislikes: 0, edit: false);
            self::$houseNumber1 = new House(
                id: 1,
                name: 'Musée Emile Guillaumin',
                presentation: 'Émile Guillaumin (1873-1951) est un paysan et écrivain. Il travaille la terre depuis son plus jeune âge et écrit ses premiers poèmes à l\'âge de 17 ans, dans le patois local du Bourbonnais. En 1892, il emménage dans l\'exploitation dite «Les Vignes», à Ygrande. C\'est ici qu\'en 1901, marqué par la lecture de "Jacquou le Croquant" d\'Eugène Le Roy, il entreprend l\'écriture de "La vie d\'un simple", son chef-d\'½uvre décrivant la condition paysanne au XIXe siècle. Malgré le succès littéraire, Emile Guillaumin n\'abandonne pas le monde paysan et contribue même à la formation d\'un syndicalisme agraire. Dans sa maison devenue musée, on découvre désormais des témoignages de la vie paysanne de l\'époque, de l\'activisme de son illustre occupant, ainsi que de sa carrière littéraire.. propriete /gestion : Propriété de la commune d\'Ygrande.',
                labelDate: 2012
            );
            self::$houseNumber2 = new House(
                id: 175,
                name: 'Maison Max Ernst et Dorothea Tanning, Le pin perdu',
                presentation: 'Max Ernst (1891-1976), peintre, sculpteur et poète allemand, est une figure majeure de l?art du XXe siècle, des mouvements dada et surréaliste. Avec son épouse, l?artiste américaine Dorothea Tanning (1910-2012), il quitte les États-Unis et rejoint Paris en 1952. Max Ernst reçoit le grand prix de la Biennale de Venise en 1954 et retrouve une vie apaisée en Touraine jusqu?en 1968. Dès 1955, la ferme de vigneron se transforme : la longère et la grange abritent l?habitation du couple et leurs ateliers respectifs. Tous les espaces pour peindre, dessiner et sculpter sont occupés. Aujourd?hui, l?atelier de peinture et la réserve des oeuvres accueillent des expositions temporaires et des conférences. L?étage, avec sa belle charpente, est dévolu au centre de documentation consacré aux artistes. Un grand jardin prolonge les bâtiments : Max et Dorothea l?ont dessiné et planté en 1959 , une serre et de multiples essences témoignent de l?intérêt du couple pour la nature. Dans le mur qui ceint le jardin, Max Ernst a encastré trois grands bas-reliefs académiques qui rappellent ses romans-collages.. propriete /gestion : Propriété privée.',
                labelDate: 2016
            );
            self::$userNumber1 = new User(id: 6, login: 'test', password: 'oui', email: 'test.38@gmail.com', blame: 0);
            self::$userNumber2 = new User(id: 100, login: 'JP', password: '20', email: 'mail@expl.fr', blame: 1);

            //-----Insertion pour respecter les préconditions-----

            DAO::get()->quickExecute('
                INSERT INTO "User"
                VALUES (1, null, \'test\', 0, \'test@unit.test\', \'testman\', false)
            ');

        } catch (Throwable $e) {
            self::fail(getFullExternalMessage("Méthode setUp\n" . $e->getMessage()));
        }
    }

    protected function tearDown(): void {
        parent::tearDown();
        self::$commentNumber1 = null;
    }

    public function test__construct() {
        try {
            self::$commentNumber1 = new Comment(id: 1, idOwner: 6, idHouse: 1, content: 'bof, un oiseau a failli me manger', rate: 3.5, likes: 0, dislikes: 0, edit: false);
            self::assertTrue(true); // pour éviter d'avoir l'erreur "this test did not perform any assertion"
        } catch (Exception) {
            self::fail("La création d'un commentaire ne devrait pas provoquer d'exception");
        }
    }

    public function testRemoveDislike() {
        try {
            self::$commentNumber1->removeDislike();
            $value = self::$commentNumber1->getDislikes();
            $expected = 0;
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value, "Dislike incorrect : $value, attendu $expected");
    }

    public function testUpdateComment() {
        try {
            self::$commentNumber1->setContent("Pas si bien");
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        try {
            self::$commentNumber1->updateComment();
            self::assertTrue(true); // pour éviter d'avoir l'erreur "this test did not perform any assertion"
        } catch (Exception) {
            self::fail("La méthode updateComment ne devrait pas provoquer d'exception");
        }
    }

    public function testAddDislike() {
        try {
            self::$commentNumber1->addDislike();
            $value = self::$commentNumber1->getDislikes();
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals(1, $value, "Dislike incorrect : $value, attendu 1");
    }

    /**
     * @throws Exception
     */
    public function testGetHouse() {
        self::assertEquals(self::$houseNumber1, self::$commentNumber1->getHouse(),
            'En théorie, c\'est la maison numéro 1 qui est utilisée. Elle devrait avoir ces attributs.');
        self::assertEquals(self::$houseNumber2, self::$commentNumber2->getHouse(),
            'En théorie, c\'est la maison numéro 175 qui est utilisée. Elle devrait avoir ces attributs.');
    }

    public function testDeleteComment() {
        try {
            self::$commentNumber1->deleteComment();
            self::assertTrue(true); // pour éviter d'avoir l'erreur "this test did not perform any assertion"
        } catch (Exception) {
            self::fail("La méthode deleteComment ne devrait pas provoquer d'exception");
        }
    }

    public function testAddReporting() {
        try {
            self::$commentNumber1->addReporting(self::$userNumber1, 'categorie 1', 'template');
            $value = self::$commentNumber1->getReporting();
            $expected = 1;
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, count($value), "Report incorrect : $value, attendu $expected");
    }

    public function testRemoveLike() {
        try {
            self::$commentNumber1->removeLike();
            $value = self::$commentNumber1->getLikes();
            $expected = 0;
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value, "Like incorrect : $value, attendu $expected");
    }

    public function testAddLike() {
        try {
            self::$commentNumber1->addLike();
            $value = self::$commentNumber1->getLikes();
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals(1, $value);
    }

    public function testCreateComment() {
        Comment::createComment("bien", 3.5, 0, 0, 1, 6)->deleteComment();

        self::assertTrue(true); // pour éviter d'avoir l'erreur "this test did not perform any assertion"
    }

    /**
     * @throws Exception
     */
    public function testGetOwner() {
        self::assertEquals(self::$userNumber1, self::$commentNumber1->getOwner(),
            'En théorie, c\'est l\'utilisateur numéro 6 qui est utilisé. Il devrait avoir ces attributs.');
        self::assertEquals(self::$userNumber2, self::$commentNumber2->getOwner(),
            'En théorie, c\'est l\'utilisateur numéro 100 qui est utilisé. Il devrait avoir ces attributs.');
    }

    public function testReadComment() {
        try {
            $nb = Comment::getLastId();
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        try {
            if ($nb !== null)
                Comment::readComment($nb);
            self::assertTrue(true); // pour éviter d'avoir l'erreur "this test did not perform any assertion"
        } catch (Exception) {
            self::fail("La méthode readComment ne devrait pas provoquer d'exception");
        }
    }
}
