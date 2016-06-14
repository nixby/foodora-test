<?php

namespace FoodoraTest;

use FoodoraTest\DB\Repository\VendorScheduleRepository;
use FoodoraTest\DB\Entity\VendorSchedule;
use FoodoraTest\DB\Entity\VendorSpecialDay;
use \PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class VendorScheduleRepositoryTest extends TestCase
{
    /**
     * @var VendorScheduleRepository $vsRepository test stub
     */
    private $vsRepository;
    
    protected function setUp()
    {
        parent::setUp();
        $this->vsRepository = $this->createMock(VendorScheduleRepository::class);
    }
    
    public function testConvertToVendorScheduleFrom()
    {
        $vendorSpecialDay = new VendorSpecialDay();
        $vendorSchedule = new VendorSchedule();
        $this->vsRepository->method('convertToVendorScheduleFrom')
             ->willReturn($vendorSchedule);
        $this->assertSame($vendorSchedule, $this->vsRepository->convertToVendorScheduleFrom($vendorSpecialDay));
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
