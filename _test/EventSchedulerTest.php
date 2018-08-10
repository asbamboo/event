<?php
namespace asbamboo\event\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\event\EventScheduler;

class EventSchedulerTest extends TestCase
{
    protected $test_value = 0;

    public function testOn()
    {

        EventScheduler::instance()->on('test_event_name', function($arg1, $arg2){
            $this->test_value = $arg1 + $arg2;
        }, 99);

        EventScheduler::instance()->on('test_event_name', function($arg1, $arg2){
            $this->test_value = $arg1 * $arg2;
        }, 0);

        $arg1 = 1;
        $arg2 = 2;
        EventScheduler::instance()->on('test_event_name', $arg1, $arg2);

        $this->assertEquals($this->test_value, 2);
    }
}