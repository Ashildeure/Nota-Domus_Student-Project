<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../model/user.class.php';
require_once __DIR__ . '/../model/DAO.class.php';
require_once __DIR__ . '/../model/person.class.php';
require_once __DIR__ . '/../model/house.class.php';
require_once __DIR__ . '/../model/comment.class.php';
require_once __DIR__ . '/../model/notification.class.php';

class UserTest extends TestCase {
    protected function setUp(): void {
        parent::setUp();
        global $databaseName;
        $databaseName = 'notadomus_test';
    }

    /**
     * @throws Exception
     */
    public function testAddBlame(): void {
        $user = new User(10, "testLogin", "testPassword", "testEmail@example.com", 0);
        $this->assertEquals(0, $user->getBlame());
        $user->addBlame();
        $this->assertEquals(1, $user->getBlame());
    }

    /**
     * @throws Exception
     */
    public function testAddFriend(): void {
        DAO::get()->quickExecute('            
            DELETE
            FROM Friend
            WHERE (idUser1 = :id1
                AND idUser2 = :id2)
               OR (idUser1 = :id2
                AND idUser2 = :id1)',
            [':id1' => 6, ':id2' => 100]
        );

        $user1 = new User(6, "test", "oui", "test.38@gmail.com", 0);
        $user2 = new User(100, "JP", "20", "mail@expl.fr", 0);

        $user1->addFriend($user2);
        $this->assertCount(1, $user1->getFriends());
    }

    /**
     * @throws Exception
     */
    public function testDeleteFriend() {
        DAO::get()->quickExecute('            
            DELETE
            FROM friend
            WHERE (iduser1 = :id1 AND iduser2 = :id2)
               OR (iduser1 = :id2 AND iduser2 = :id1)',
            [':id1' => 6, ':id2' => 100]
        );
        $user1 = new User(6, "test", "oui", "test.38@gmail.com", 0);
        $user2 = new User(100, "JP", "20", "mail@expl.fr", 0);
        $user1->addFriend($user2);
        $user1->removeFriend($user2);
        $this->assertCount(0, $user1->getFriends());
    }

    /**
     * @throws Exception
     */
    public function testAddAndRemoveFavorite() {
        $user = new User(10, "ex", "pass", "user@example.com", 0);

        $house = new House(10, "house", "description", 147474);

        $user->addFavorite($house);

        $this->assertCount(1, $user->getFavorites());

        $user->deleteFavorite($house);

        $this->assertCount(0, $user->getFavorites());
    }

    /**
     * methode compliquee car il faut des utilisateurs dans la base pour faire le select
     */
    //    public function testAddComment() {
    //        $user = new User("qsdfghj", "pass", "user@example.com", 0);
    //
    //        $house = new House(1, "Maison Test", "Une belle maison", 12345);
    //
    //        $user->addComment($house, $user, "Un comm", 4, 2, 1, 0);
    //
    //        $this->assertCount(1, $user->getComments());
    //
    //        $comments = $user->getComments();
    //        $this->assertEquals("Super maison!", $comments[0]->getText());
    //        $this->assertEquals(4.5, $comments[0]->getRating());
    //        $this->assertEquals($house->getId(), $comments[0]->getHouseId());
    //    }

    /**
     * @throws Exception
     */
    public function testBan() {
        $user = new User(10, "ri", "on", "det@example.com", 0);

        $user->ban();
        $this->assertEquals('', $user->getEmail());
        $this->assertEquals('', $user->getLogin());
    }

    /**
     * @throws Exception
     */
    public function testReadUserByLogin() {
        $user = User::readUserByLogin("flav");
        $this->assertEquals("flav@flav.com", $user->getEmail());
    }

    /**
     * @throws Exception
     */
    public function testUserExists() {
        $user = User::readUserByLogin("flav");
        $this->assertTrue(User::userExists($user->getEmail()));

        $user2 = new User(30, "user2", "pass2", "user2@example.com", 0);
        $this->assertFalse(User::userExists($user2->getLogin()), "il existe pas");
    }

    /**
     * @throws Exception
     */
    public function testGetFriends() {
        // FIXME: Ce test n'est plus à jour - JP a 6 amis maintenant
        $user = User::readUserById(100);
        $user->getFriends();
        $this->assertCount(2, $user->getFriends());
    }

    public function testReadLike() {
        $user = User::readUserById(100);
        $users = $user->readLike("jp");
        $this->assertCount(3, $users);
        $users = $user->readLike("JP");
        $this->assertCount(3, $users);
        $users = $user->readLike("jP");
        $this->assertCount(3, $users);
    }
}
