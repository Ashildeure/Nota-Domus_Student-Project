<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../model/user.class.php';
require_once __DIR__ . '/../model/DAO.class.php';
require_once __DIR__ . '/../model/person.class.php';
require_once __DIR__ . '/../model/house.class.php';
require_once __DIR__ . '/../model/ohAdmin.class.php';

class OhAdminTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        global $databaseName;
        $databaseName = 'notadomus_test';
    }

    /**
     * @throws Exception
     */
    public function testGetHouse() {

        // FIXME: L'utilisateur 25 n'existe plus au moment où j'écris ce message
        $ohAdmin = OhAdmin::readOhAdmin(25);

        $house = $ohAdmin->getHouse();

        $this->assertEquals(130, $house->getId());
    }

    public function testCreateOhAdmin() {
        //info de l'OhAdmin
        $login = "superGestionaire";
        $password = "compliquer";
        $email = "email@email.com";
        $idhouse = 130;

        // test
        OhAdmin::createOhAdmin($login, $password, $email, $idhouse);

        //-Nettoyage BD
        DAO::get()->quickExecute('DELETE FROM "User" WHERE login = \'superGestionaire\'');

        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testReadOhAdmin() {
        // FIXME: L'utilisateur 25 n'existe plus au moment où j'écris ce message
        // attendu
        $id = 25;
        $login = "superGestionaire";
        $password = '$2y$10$sCI9zfGSpQhjuXqfKs7oXOAGR/RG63CT3Ow4sUWSy49lIB/eeUIA6';
        $email = "email@email.com";
        $blame = 0;
        $idhouse = 130;
        $ohAdmin_expected = new OhAdmin($id, $login, $password, $email, $blame, $idhouse);

        $ohAdmin_real = OhAdmin::readOhAdmin($id);

        //verrification
        $this->assertEquals($ohAdmin_expected, $ohAdmin_real);
    }

    public function testUpdateOhAdmin() {
        /**
         * TODO: faire ce test
         */

    }
}