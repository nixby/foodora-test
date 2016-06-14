<?php

namespace FoodoraTest;

use FoodoraTest\DB\Connection;
use FoodoraTest\DBConfig;
use FoodoraTest\DB\Repository\VendorScheduleRepository;
use FoodoraTest\DB\Repository\VendorSpecialDayRepository;

require __DIR__ . '/../vendor/autoload.php';

/**
 * ConvertDataCommand file to migrate data
 * from vendor_schedue to vendor_specail_day table
 *
 * @package  FoodoraTest
 * @version   0.0.1
 * @license  MIT
 *
 */
class ConvertDataCommand
{
    /**
     * @var string START_DATE start date to migrate data from
     */
    const START_DATE = '2015-12-21';

    /**
     * @var string END_DATE end date to migrate data until
     */
    const END_DATE = '2015-12-27';

    /**
     * @var \PDO instance $PDOConnection connection to the database
     */
    private $PDOConnection;

    /**
     * @var VendorScheduleRepository $vendorScheduleRepository repository
     */
    private $vendorScheduleRepository;

    /**
     * @var VendorSpecialDayRepository $vendorSpecialDayRepository repository
     */
    private $vendorSpecialDayRepository;

    /**
     * Class constructor. Saves information
     * necessary to establish a new DB connection.
     * Creates two repository to migrate data.
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
            throw new \Exception(
                'Could not establish connection to the DB: ' .
                $ex->getMessage()
            );
        }
        $this->vendorScheduleRepository = new VendorScheduleRepository(
            $this->PDOConnection
        );
        $this->vendorSpecialDayRepository = new VendorSpecialDayRepository($this->PDOConnection);
    }

    /**
     * Drops backup table if exists and create it again,
     * copies data to the backup.
     * @param void
     * @return void
     */
    public function doBackup()
    {
        $this->vendorScheduleRepository->dropBackupTable();
        $this->vendorScheduleRepository->createNewAndBackupOldTable();
    }

    /**
     * Removes schedules that have corresponding special days.
     * @todo try catch would be good
     * @param void
     * @return void
     */
    public function removeVendorSchedulesBySpecialDays()
    {
        $ids = $this->vendorScheduleRepository->findIdsBySpecialDaysBetween(self::START_DATE, self::END_DATE);
        foreach ($ids as $id) {
            $this->vendorScheduleRepository->deleteVendorScheduleById($id);
        }
    }

    /**
     * Copies special day to replace regular schedules.
     * @todo try catch would be good
     * @param void
     * @return void
     */
    public function copySpecialDaysToVendorSchedules()
    {
        $specialDays = $this->vendorSpecialDayRepository->
            findOpenedSpecialDaysBetweenDates(
                self::START_DATE,
                self::END_DATE
            );
        foreach ($specialDays as $day) {
            $schedule = $this->vendorScheduleRepository->convertToVendorScheduleFrom($day);
            $this->vendorScheduleRepository->saveVendorSchedule($schedule);
        }
    }
}
/**
 * @todo create a separate file to move this code to, wrap it in try-catch block, also set timezone and utf8 charset
 */
$convertDataCommand = new ConvertDataCommand(DBConfig::HOST, DBConfig::NAME, DBConfig::USER, DBConfig::PASSWORD);
print('Starting to backup data. Please wait. It will take a while.\n');
$convertDataCommand->doBackup();
print('Backup table is ready. Starting to delete data.\n');
$convertDataCommand->removeVendorSchedulesBySpecialDays();
print('Data is removed. Replacing regular schedules with the special ones.\n');
$convertDataCommand->copySpecialDaysToVendorSchedules();
print('Data converting is finished. Do not forget to restore your data later.\n');
