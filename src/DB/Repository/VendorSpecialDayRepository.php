<?php

namespace FoodoraTest\DB\Repository;

use FoodoraTest\DB\Entity\VendorSpecialDay;

/**
 * VendorSpecialDayRepository is repository like class
 * that  manipulates the data of VendorSpecialDay enity
 * for the vendor_special_day table
 * @package   FoodoraTest\DB\Repository
 * @version   0.0.1
 * @license   MIT
 */
class VendorSpecialDayRepository
{
    /**
     * @var \PDO instance $PDOConnection connection to the database
     */
    private $PDOConnection;

    /**
     * @var string TABLE_NAME name of the table vendor_special_day
     */
    const TABLE_NAME = 'vendor_special_day';

    /**
     * @var string VENDOR_TABLE_NAME name of the table vendor
     */
    const VENDOR_TABLE_NAME = 'vendor';

    /**
     * @var string VENDOR_SCHEDULE_TABLE_NAME name of the vendor_schedule table
     */
    const VENDOR_SCHEDULE_TABLE_NAME = 'vendor_schedule';

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
     * Finds special days when restaurantss are open inbetween given time period.
     * @param string $startDate beginning of the time period to search in.
     * @param string $endDate end of the time period to search in.
     * @return array $vendorSpecialDays array of VendorSpecialDay elements
     */
    public function findOpenedSpecialDaysBetweenDates($startDate, $endDate)
    {
        $results = [];
        $vendorSpecialDays = [];
        $query = "SELECT * FROM `" . self::TABLE_NAME . "` AS VSD "
                . "INNER JOIN `" . self::VENDOR_TABLE_NAME. "` AS V "
                . "ON V.ID = VSD.VENDOR_ID "
                . "WHERE VSD.SPECIAL_DATE BETWEEN :start_date AND :end_date "
                . "AND UPPER(VSD.EVENT_TYPE) = 'OPENED';";
        $statement = $this->PDOConnection->prepare($query);
        $statement->bindParam(':start_date', $startDate);
        $statement->bindParam(':end_date', $endDate);
        $statement->execute();
        
        $results = $statement->fetchAll(\PDO::FETCH_OBJ);
        
        foreach ($results as $res) {
            $vsd = new VendorSpecialDay();
            $vsd->setId($res->id);
            $vsd->setVendorId($res->vendor_id);
            $vsd->setSpecialDate($res->special_date);
            $vsd->setEventType($res->event_type);
            $vsd->setAllDay($res->all_day);
            $vsd->setStartHour($res->start_hour);
            $vsd->setEndHour($res->stop_hour);
            
            $vendorSpecialDays[] = $vsd;
        }
        return $vendorSpecialDays;
    }
}
