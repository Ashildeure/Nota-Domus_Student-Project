<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../model/user.class.php';
require_once __DIR__ . '/../model/DAO.class.php';
require_once __DIR__ . '/../model/person.class.php';
require_once __DIR__ . '/../model/house.class.php';
require_once __DIR__ . '/../model/ohAdmin.class.php';

class ReportTest extends TestCase {
    protected function setUp(): void {
        parent::setUp();
        global $databaseName;
        $databaseName = 'notadomus_test';
    }

    public function testConstruct() {
        $report = new Report(127, 'test report', 'il est null');
        self::assertTrue(true);
        $user = new User(100, "JP", "20", "mail@expl.fr", 0);
        $report = new Report($user, 'test report', 'il est null');
        self::assertTrue(true);
    }

    public function testCreateReport() {
        $report = Report::createReport(127, 100, 'test report', 'il est null');
        self::assertTrue(true);

    }

    public function testReadReport() {
        $report = Report::readReport(127, 100);
        $expected = new Report (100, 'test report', 'il est null');
        self::assertEquals($report, $expected);
    }

    public function testDeleteReport() {
        Report::deleteReport(127, 100);
        self::assertTrue(true);
    }
}