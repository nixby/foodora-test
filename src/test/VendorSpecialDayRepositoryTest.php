<?php

namespace FoodoraTest;

use FoodoraTest\DB\Repository\VendorSpecialDayRepository;
use FoodoraTest\DB\Entity\VendorSpecialDay;
use \PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';
/**
 * VendorSpecialDayRepositoryTest
 * @package   FoodoraTest
 * @version   0.0.1
 * @license   MIT
 */
class VendorSpecialDayRepositoryTest extends TestCase
{
    /**
     * @var VendorSpecialDayRepository $vsdRepository test stub
     */
    private $vsdRepository;
    
    protected function setUp()
    {
        parent::setUp();
        $this->vsdRepository = $this->createMock(VendorSpecialDayRepository::class);
    }
    
    public function testFindOpenedSpecialDaysBetweenDates()
    {
        $vendorSpecialDay = new VendorSpecialDay();
        $vsdArray = [$vendorSpecialDay];
        $this->vsdRepository->method('findOpenedSpecialDaysBetweenDates')
             ->willReturn($vsdArray);
        $this->assertContains($vendorSpecialDay, $this->vsdRepository->findOpenedSpecialDaysBetweenDates('2015-12-21', '2015-12-27'));
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
