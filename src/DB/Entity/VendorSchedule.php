<?php

namespace FoodoraTest\DB\Entity;

/**
 * VendorSchedule is entity-like class
 * that represents data of vendor_schedule table
 * @package   FoodoraTest\DB\Entity
 * @version   0.0.1
 * @license   MIT
 */
class VendorSchedule
{
    /**
     * @var string $id table id
     */
    private $id;
    
    /**
     * @var string $vendorId foreign key on vendor table
     */
    private $vendorId;
    
    /**
     * @var string $weekday day of the week (1 = Monday, .., 7 = Sunday)
     */
    private $weekday;
    
    /**
     * @var string $allDay whether opened all day
     * (1 = whole day, 0 = open hours specified in $startHour and $endHour)
     */
    private $allDay;
    
    /**
     * @var string $startHour beginning of opening hours
     */
    private $startHour;
    
    /**
     * @var string $endHour end of opening hours
     */
    private $endHour;
    
    /**
     * Sets the id of the object.
     * @param string $id new id to be set.
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Returns the id of the current object.
     * @param void
     * @return string current id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the vendor_id of the object.
     * @param string $vendorId vendor_it to be set.
     * @return void
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;
    }
    
    /**
     * Gets the vendor_id of the current object.
     * @param void
     * @return string current vendor_id.
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }
    
    /**
     * Sets the day of week for the object.
     * @param string $weekday day of week to be set.
     * @return void
     */
    public function setWeekday($weekday)
    {
        $this->weekday = $weekday;
    }
    
    /**
     * Gets the day of week of the current object.
     * @param void
     * @return string day of the week.
     */
    public function getWeekday()
    {
        return $this->weekday;
    }
    
    /**
     * Sets whether current restaurant is opened the whole day.
     * @param string $allDay 1 or 0.
     * @return void
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;
    }
    
    /**
     * Gets whether current restaurant is opened the whole day.
     * @param void
     * @return string 1 or 0.
     */
    public function getAllDay()
    {
        return $this->allDay;
    }
    
    /**
     * Sets the beginning of openning hours.
     * @param string $startHour the starting hour to be set.
     * @return void
     */
    public function setStartHour($startHour)
    {
        $this->startHour = $startHour;
    }
    
    /**
     * Gets the beginning of openning hours.
     * @param void
     * @return string the starting hour of the current restaurant.
     */
    public function getStartHour()
    {
        return $this->startHour;
    }
    
    /**
     * Sets the end of openning hours.
     * @param string $endHour time when restaurant is closing.
     * @return void
     */
    public function setEndHour($endHour)
    {
        $this->endHour = $endHour;
    }
    
    /**
     * Gets the end of openning hours.
     * @param void
     * @return string the end wokring hour of the current restaurant.
     */
    public function getEndHour()
    {
        return $this->endHour;
    }
}
