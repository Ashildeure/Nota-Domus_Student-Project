<?php
require_once __DIR__ . '/../model/user.class.php';
require_once __DIR__ . '/../model/notification.class.php';
require_once __DIR__ . '/../model/moderator.class.php';
require_once __DIR__ . '/../model/comment.class.php';

use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        global $databaseName;
        $databaseName = 'notadomus_test';
    }

    public function test__construct() {
        try {
            // infos pour la creation des notif
            $user = User::readUserById(100);
            $modo = Moderator::readModerator(18);
            $message = "notification modo test";
            $friend = Comment::readComment(21);

            // test du constructeur pour un User
            $notificationUser = new Notification('10', $user, $message, $friend);
            // test du constructeur pour un Moderateur
            $notificationModo = new Notification('10', $modo, $message, $friend);

            // Si aucune exception n'est lancée, ce test passe
            $this->assertTrue(true);  // Juste pour indiquer que le test a passé
        } catch (Exception $e) {
            $this->fail('Une exception a été lancée : ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function testCreateNotificationForUser() {
        //info de la notification
        $user = User::readUserById(100);
        $message = "notification test";
        $friend = User::readUserById(10);

        // test
        $idNotifCree = Notification::createNotification($user, $message, $friend)->getId();

        //Verrification de l'ecriture

        $notification = Notification::readNotification($idNotifCree);
        $this->assertNotNull($notification);
    }

    /**
     * Précondition : readNotification fonctionne
     * @throws Exception
     */
    public function testCreateNotificationForModerator() {
        //info de la notification
        $modo = Moderator::readModerator(18);
        $message = "notification modo test";
        // FIXME: Le commentaire 21 n'existe plus au moment où j'écris ce message
        $comment = Comment::readComment(21);

        // test
        Notification::createNotification($modo, $message, $comment);

        //Verrification de l'ecriture
        $notification = Notification::readNotification(Notification::getLastId());
        $this->assertNotNull($notification);
    }

    /**
     * Précondition : il existe une ligne notification (idnotif=3, idowner=100, requestfriend=10, reportcomment=NULL, message="notification test") dans la BD
     * @throws Exception
     */
    public function testReadNotification() {
        //

        // Valeur attendu
        $user = User::readUserById(100);
        $friend = User::readUserById(10);
        $notification_expected = new Notification(3, $user, "notification test", $friend,);

        // FIXME: la notification 3 n'existe plus au moment où j'écris ce message
        $notification_real = Notification::readNotification(3);

        // Verrification de la lecture
        $this->assertEquals($notification_expected, $notification_real);
    }

    /**
     * @throws Exception
     */
    public function testDeleteNotification() {
        // Precondition readNotification et createNotification marche

        // Creation de la notif qui vas etre delete
        $modo = Moderator::readModerator(18);
        $message = "notification modo test";
        $friend = Comment::readComment(21);
        // FIXME: Le commentaire 21 n'existe plus au moment où j'écris ce message
        $notification = Notification::createNotification($modo, $message, $friend);
        $idNotifASup = $notification->getId();
        //test
        $notification->deleteNotification();

        // si le read ne renvoi pas d'erreur le delete n'as pas marché
        $this->expectExceptionMessage("Notification $idNotifASup non trouvée dans la base de données");
        Notification::readNotification($idNotifASup);

    }
}
