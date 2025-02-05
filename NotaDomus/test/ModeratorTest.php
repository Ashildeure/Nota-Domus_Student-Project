<?php
//---Import---
require_once __DIR__ . '/../model/moderator.class.php';
require_once __DIR__ . '/../model/comment.class.php';

use PHPUnit\Framework\TestCase;

class ModeratorTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        global $databaseName;
        $databaseName = 'notadomus_test';
    }

    public function test__construct() {
        try {
            $id = 20;
            $login = "ModerateurTest";
            $pasword = "compliquer";
            $email = "test@email.com";
            new Moderator($id, $login, $pasword, $email);
            $this->assertTrue(true);
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
    }

    public function testDeleteComment() {
        try {
            $modo = Moderator::readModerator(18);
            Comment::createComment("Je ne devris pas exister", 0, 0, 0, 0, 4, 130);
            $comment = Comment::readComment(Comment::getLastId());
            $modo->deleteComment($comment);

            $this->assertTrue(true);
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
    }

    /**
     * @throws Exception
     */
    public function testReadModerator() {
        // resultat attendu
        $id = 18;
        $login = "LeDaronAJP";
        $pasword = "securisé";
        $email = "test@gmail.com";
        $modo_expected = new Moderator($id, $login, $pasword, $email);

        $modo = Moderator::readModerator(18);

        $this->assertEquals($modo, $modo_expected);
    }
}
