<?php
namespace asbamboo\event\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\event\_test\fixtures\EventListener20180108;
use asbamboo\event\EventListener;
use asbamboo\event\EventScheduler;

class EventListenerTest extends TestCase
{
    public function testSet()
    {
        EventListener::instance()->set('test.event.register', EventListener20180108::class, 'testMethod', [1,2,3], 1);
        $test_event_register    = EventListener::instance()->get('test.event.register');
        $this->assertEquals($test_event_register[0]['class'], EventListener20180108::class);
        $this->assertEquals($test_event_register[0]['method'], 'testMethod');
        $this->assertEquals($test_event_register[0]['arguments'], [1,2,3]);
        $this->assertEquals($test_event_register[0]['priority'], 1);
    }

    public function testHas()
    {
        $this->assertTrue(EventListener::instance()->has('test.event.register'));
    }

    public function testBind()
    {
        EventScheduler::instance()->on('test.event.register', 10);

        $this->assertEquals(EventListener20180108::$test_var1, 10);

        $this->assertEquals(EventListener20180108::$test_var2, 20);

        $this->assertEquals(EventListener20180108::$test_var3, 30);
    }
}