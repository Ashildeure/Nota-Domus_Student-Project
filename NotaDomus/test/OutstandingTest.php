<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../model/outstanding.class.php';
require_once __DIR__ . '/../model/DAO.class.php';
require_once __DIR__ . '/../model/house.class.php';

class OutstandingTest extends TestCase {

    /**
     * @var Outstanding|null Ceci est une fixture. Elle est initialisée dans `setUp`
     */
    public static ?Outstanding $outstanding;

    protected function setUp(): void {
        global $databaseName;
        $databaseName = 'notadomus_test';
        try {
            parent::setUp();
            self::$outstanding = new Outstanding(
                1,
                "Molière",
                "test",
                2025
            );

        } catch (Throwable $e) {
            self::fail(getFullExternalMessage("Méthode setUp\n" . $e->getMessage()));
        }
    }

    protected function tearDown(): void {
        parent::tearDown();
        self::$outstanding = null;
    }

    /**
     * @throws Exception
     */
    public function testGetHouses() {
        $house = House::readHouse(115);
        $houses = [$house];
        $outstanding = new Outstanding(2, "Eugène Delacroix", "Arts et architecture", 19);
        self::assertEquals($outstanding->getHouses(), $houses);
    }

    /**
     * @throws Exception
     */
    public function testReadOutstanding() {
        $outstanding = new Outstanding(2, "Eugène Delacroix", "Arts et architecture", 19);
        $lecture = Outstanding::readOutstanding(2);
        self::assertEquals($outstanding, $lecture);
    }

    /**
     * @throws Exception
     */
    public function testGetRomanNumber() {
        $table = ["XIV", "XV", "XVI", "XVII", "XVIII", "XIX", "XX", "XXI"];
        $epoque = 14;
        for ($i = 0; $i <= 7; $i++) {
            $outstanding = new Outstanding(1, "Molière", "test", $epoque);
            self::assertEquals($outstanding->getRomanNumber(), $table[$i]);
            $epoque++;
        }
    }

    /**
     * @throws Exception
     */
    public function testGetName() {
        $outstanding = new Outstanding(1, "Molière", "test", 20);
        self::assertEquals("Molière", $outstanding->getName());
    }

    /**
     * @throws Exception
     */
    public function testGetEpoque() {
        $outstanding = new Outstanding(1, "Molière", "test", 20);
        self::assertEquals(20, $outstanding->getEpoque());
    }

    /**
     * @throws Exception
     */
    public function testGetId() {
        $outstanding = new Outstanding(1, "Molière", "test", 20);
        self::assertEquals(1, $outstanding->getId());
    }

    /**
     * @throws Exception
     */
    public function testGetType() {
        $outstanding = new Outstanding(1, "Molière", "test", 20);
        self::assertEquals("test", $outstanding->getType());
    }

    /**
     * @throws Exception
     */
    public function testGetWorks() {
        $table = ["La Mort de Sardanapale", "Les Femmes d'Alger", "La Liberté guidant le peuple"];
        $outstanding = new Outstanding(2, "Eugène Delacroix", "Arts et architecture", 19);
        self::assertEquals($outstanding->getWorks(), $table);
    }

}
