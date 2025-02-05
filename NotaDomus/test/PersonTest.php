<?php
require_once __DIR__ . '/../model/user.class.php';

use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase {
    private User $user;

    /**
     * Méthode setUp : appelée avant chaque test
     * @throws Exception
     */
    protected function setUp(): void {
        global $databaseName;
        $databaseName = 'notadomus_test';
        $this->user = new User(10, 'JeanTest', 'dur', 'mail@mail.mail', 0);
        parent::setUp();
    }

    // Méthode tearDown : appelée après chaque test
    protected function tearDown(): void {
        unset($this->user); // Libérer les ressources si nécessaire
        parent::tearDown();
    }


    /**
     * @throws Exception
     */
    public function testSetLogin() {
        $old = $this->user->getLogin();
        $this->user->setLogin('login');
        $this->assertNotEquals($old, $this->user->getLogin());
    }

    public function testSetPassword() {
        $old = $this->user->getPassword();
        // FIXME: setPassword lit l'utilisateur 10 dans la BD et son mdp est déjà 'oui'...
        $this->user->setPassword('oui');
        $this->assertNotEquals($old, $this->user->getPassword());
    }

    public function testSetEmail() {
        $old = $this->user->getEmail();
        $this->user->setEmail('darkSasuke@Yahoo.com');
        $this->assertNotEquals($old, $this->user->getEmail());
    }

    /**
     * @throws Exception
     */
    public function testGetRole() {
        $user1 = User::readUserById(18);
        $this->assertEquals("moderator", $user1->getRole());
        // FIXME: L'utilisateur 175 n'existe plus au moment où j'écris ce message
        $user2 = User::readUserById(175);
        $this->assertEquals("ohAdmin", $user2->getRole());
        $user3 = User::readUserById(100);
        $this->assertEquals("user", $user3->getRole());
    }
}
