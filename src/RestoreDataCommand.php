<?php

namespace FoodoraTest;

use FoodoraTest\DB\Connection;
use FoodoraTest\DBConfig;
use FoodoraTest\DB\Repository\VendorScheduleRepository;

require __DIR__ . '/../vendor/autoload.php';

/**
 * RestoreDataCommand file to restore data
 * from tmp_vendor_schedue to vendor_schedue table
 *
 * @package  FoodoraTest
 * @version   0.0.1
 * @license  MIT
 *
 */
class RestoreDataCommand
{
    /**
     * @var \PDO instance $PDOConnection connection to the database
     */
    private $PDOConnection;

    /**
     * @var VendorScheduleRepository $vendorScheduleRepository repository
     */
    private $vendorScheduleRepository;

      /**
     * Class constructor. Saves information
     * necessary to establish a new DB connection.
     * Creates VendorScheduleRepository repository to restore data.
     * @param string $host database hostname.
     * @param string $name database name.
     * @param string $user database user.
     * @param string $password passoword of the database user.
     * @throws \PDOException if not possible to connect with the db
     * @return void
     */
    public function __construct($host, $name, $user, $password)
    {
        $connection = new Connection($host, $name, $user, $password);
        try {
            $this->PDOConnection = $connection->connect();
        } catch (\PDOException $ex) {
            throw new \Exception('Could not establish connection to the DB: ' . $ex->getMessage());
        }
        $this->vendorScheduleRepository = new VendorScheduleRepository($this->PDOConnection);
    }

    /**
     * Clears vendor_schedule table.
     * Copies data from the backup table to the vendor_schedule.
     * Deletes the backup table.
     * @todo check if the backup table is still there to restore data
     * @param string $id new id to be set.
     * @return void
     */
    public function restoreVendorSchedules()
    {
        $this->vendorScheduleRepository->emptyVendorSchedules();
        $this->vendorScheduleRepository->restoreVendorSchedules();
        $this->vendorScheduleRepository->dropBackupTable();
    }
}

/**
* @todo create a separate file to move this code to, wrap it in try-catch block
*/
$restoreDataCommand = new RestoreDataCommand(DBConfig::HOST, DBConfig::NAME, DBConfig::USER, DBConfig::PASSWORD);
print('Starting to restore the data. Please wait. It will take a while.\n');
$restoreDataCommand->restoreVendorSchedules();
print('Data is restored.\n');
