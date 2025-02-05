<?php


use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../model/location.class.php';

class LocationTest extends TestCase {

    /**
     * @var Location|null Ceci est une fixture. Elle est initialisée dans `setUp`
     */
    public static ?Location $location;

    protected function setUp(): void {
        global $databaseName;
        $databaseName = 'notadomus_test';
        parent::setUp();
        self::$location = new Location(
            1,
            "Roubaix",
            59100,
            "Nord",
            "Hauts-de-France",
            59,
            10,
            "12 rue truc",
            "50° 41 24″ nord",
            "3° 10 54″ est"
        );
    }

    protected function tearDown(): void {
        parent::tearDown();
        self::$location = null;
    }

    public function testGetAddresse() {
        try {
            $value = self::$location->getAddresse();
            $expected = "12 rue truc";
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function testGetLongitude() {
        try {
            $value = self::$location->getLongitude();
            $expected = "3° 10 54″ est";
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function testGetDepartmentName() {
        try {
            $value = self::$location->getDepartmentName();
            $expected = "Hauts-de-France";
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function testGetLatitude() {
        try {
            $value = self::$location->getLatitude();
            $expected = "50° 41 24″ nord";
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function testGetRegionName() {
        try {
            $value = self::$location->getRegionName();
            $expected = "Nord";
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function testGetCity() {
        try {
            $value = self::$location->getCity();
            $expected = "Roubaix";
            self::assertEquals($expected, $value);
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
    }

    public function testReadLocation() {
        try {
            $value = Location::readLocation(65);
            $expected = new Location(65, "Ornans", 25290, "Bourgogne-Franche-Comté", "Doubs", 27, 25, "3 Av. Président Wilson, 25290 Ornans, France", "47.10703", "6.144694");
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function testGetDepartmentCode() {
        try {
            $value = self::$location->getDepartmentCode();
            $expected = 10;
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function testGetPostalCode() {
        try {
            $value = self::$location->getPostalCode();
            $expected = 59100;
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function test__construct() {
        try {
            self::$location = new Location(1, "Roubaix", 59100, "Nord", "Hauts-de-France", 59, 10, "12 rue truc", "50° 41 24″ nord", "3° 10 54″ est");
            $this->assertTrue(true);
        } catch (Exception) {
            self::fail("La création d'un emplacement ne devrait pas provoquer d'exception");
        }
    }

    public function testGetRegionCode() {
        try {
            $value = self::$location->getRegionCode();
            $expected = 59;
        } catch (Throwable $e) {
            self::fail(getFullExternalMessage($e->getMessage() . $e->getTraceAsString()));
        }
        self::assertEquals($expected, $value);
    }

    public function testReadRegions() {
        $regions = Location::readRegions();
        $this->assertEquals(18, sizeof($regions));
    }

    public function testReadDepartments() {
        $departments = Location::readDepartments();
        $this->assertEquals(101, sizeof($departments));
    }
}
