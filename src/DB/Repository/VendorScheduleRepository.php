<?php

namespace FoodoraTest\DB\Repository;

use FoodoraTest\DB\Entity\VendorSchedule;
use FoodoraTest\DB\Entity\VendorSpecialDay;

/**
 * VendorScheduleRepository is repository like class
 * that manipulates the data of VendorSchedule enity
 * for the vendor_schedule table
 * @package   FoodoraTest\DB\Repository
 * @version   0.0.1
 * @license   MIT
 */
class VendorScheduleRepository
{
    /**
     * @var \PDO instance $PDOConnection connection to the database
     */
    private $PDOConnection;

    /**
     * @var string BACKUP_TABLE_NAME name of the backup table for vendor_schedule
     */
    const BACKUP_TABLE_NAME = 'tmp_vendor_schedule';

    /**
     * @var string TABLE_NAME name of the vendor_schedule table
     */
    const TABLE_NAME = 'vendor_schedule';

    /**
     * @var string VENDOR_SPECIAL_DAY_TABLE_NAME name of the vendor_special_day table
     */
    const VENDOR_SPECIAL_DAY_TABLE_NAME = 'vendor_special_day';

    /**
     * Class constructor. Saves given connection.
     * @param \PDO instance $connection database connection.
     * @return void
     */
    public function __construct(\PDO $connection)
    {
        $this->PDOConnection = $connection;
    }

    /**
     * Clears vendor_schedule table from all records.
     * @param void.
     * @return void
     */
    public function emptyVendorSchedules()
    {
        $query = "TRUNCATE TABLE `" . self::TABLE_NAME . "`";
        $statement = $this->PDOConnection->prepare($query);
        $statement->execute();
    }

    /**
     * Deletes backup table if it already exists.
     * @param void
     * @return void
     */
    public function dropBackupTable()
    {
        $query = "DROP TABLE IF EXISTS `" . self::BACKUP_TABLE_NAME . "`;";
        $statement = $this->PDOConnection->prepare($query);
        $statement->execute();
    }

    /**
     * Create new backup table and copies there all row from vendor_schedule.
     * @param void
     * @return void
     */
    public function createNewAndBackupOldTable()
    {
        $query = "CREATE TABLE `" . self::BACKUP_TABLE_NAME . "` "
                . "SELECT * FROM `" . self::TABLE_NAME . "`;";
        $statement = $this->PDOConnection->prepare($query);
        $statement->execute();
    }

    /**
     * Copies all records from backup table back to vendor_schedule table.
     * @param void
     * @return void
     */
    public function restoreVendorSchedules()
    {
        $query = "INSERT INTO `" . self::TABLE_NAME . "` "
                . "SELECT * FROM `" . self::BACKUP_TABLE_NAME . "`;";
        $statement = $this->PDOConnection->prepare($query);
        $statement->execute();
    }

    /**
     * Selects ids only of those records in vendor_schedule table
     * for those there are corresponding records in the vendor_special_day table
     * (special date lays between given dates and comply with the day of week
     * from the vendor_schedule).
     * @param string $startDate start of time interval to search in.
     * @param string $endDate end of time interval to search in.
     * @return array $results an array with found ids
     */
    public function findIdsBySpecialDaysBetween($startDate, $endDate)
    {
        $results = [];

        $query = "SELECT DISTINCT VS.ID FROM `" . self::TABLE_NAME . "` AS VS "
                . "INNER JOIN `" . self::VENDOR_SPECIAL_DAY_TABLE_NAME . "` AS VSD "
                . "ON VS.VENDOR_ID = VSD.VENDOR_ID "
                . "WHERE VSD.SPECIAL_DATE BETWEEN :start_date AND :end_date "
                . "AND VS.WEEKDAY = WEEKDAY(VSD.SPECIAL_DATE) + 1;";

        $statement = $this->PDOConnection->prepare($query);

        $statement->bindParam(':start_date', $startDate);
        $statement->bindParam(':end_date', $endDate);

        $statement->execute();
        // we could get all information, but actually only need the ids
        $results = $statement->fetchAll(\PDO::FETCH_COLUMN, 0);
        return $results;
    }

    /**
     * Converts VendorSpecialDay objects to VendorSchedule objects.
     * @param VendorSpecialDay $vendorSpecialDay special day to be converted.
     * @return VendorSchedule $vendorSchedule newly created schedule
     */
    public function convertToVendorScheduleFrom(VendorSpecialDay $vendorSpecialDay)
    {
        $vendorSchedule = new VendorSchedule();
        $vendorSchedule->setVendorId($vendorSpecialDay->getVendorId());

        $dayOfWeek = new \DateTime($vendorSpecialDay->getSpecialDate());
        // ISO-8601 numeric representation of the day of the week
        // Starts with Monday as day 1, ends with Sunday as 7
        $vendorSchedule->setWeekday($dayOfWeek->format('N'));

        $vendorSchedule->setAllDay($vendorSpecialDay->getAllDay());
        $vendorSchedule->setStartHour($vendorSpecialDay->getStartHour());
        $vendorSchedule->setEndHour($vendorSpecialDay->getEndHour());

        return $vendorSchedule;
    }

    /**
     * Saved schedule to the database.
     * @param VendorSchedule $schedule object to save.
     * @return void
     */
    public function saveVendorSchedule(VendorSchedule $schedule)
    {
        $query = "INSERT INTO `" . self::TABLE_NAME . "` "
                    . "(VENDOR_ID, "
                    . "WEEKDAY, "
                    . "ALL_DAY, "
                    . "START_HOUR, "
                    . "STOP_HOUR) "
                . "VALUES "
                    . "(:vendor_id, "
                    . ":weekday, "
                    . ":all_day, "
                    . ":start_hour, "
                    . ":stop_hour);";
        $statement = $this->PDOConnection->prepare($query);

        $statement->bindValue(':vendor_id', $schedule->getVendorId());
        $statement->bindValue(':weekday', $schedule->getWeekday());
        $statement->bindValue(':all_day', $schedule->getAllDay());
        $statement->bindValue(':start_hour', $schedule->getStartHour());
        $statement->bindValue(':stop_hour', $schedule->getEndHour());
        
        $statement->execute();
    }

    /**
     * Removed record from vendor_schedule table using given id.
     * @param string $id id of the record to be deleted.
     * @return void
     */
    public function deleteVendorScheduleById($id)
    {
        $query = "DELETE FROM `" . self::TABLE_NAME . "`"
                . " WHERE ID = :id;";
        $statement = $this->PDOConnection->prepare($query);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
