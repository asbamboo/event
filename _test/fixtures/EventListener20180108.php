<?php
namespace asbamboo\event\_test\fixtures;

class EventListener20180108
{
    public static $test_var1;
    public static $test_var2;
    public static $test_var3;
    public function __construct($test_var1, $test_var2, $test_var3)
    {
        static::$test_var1    = $test_var1;
        static::$test_var2    = $test_var2;
        static::$test_var3    = $test_var3;
    }

    public function testMethod(int $test_param)
    {
        static::$test_var1    *= $test_param;
        static::$test_var2    *= $test_param;
        static::$test_var3    *= $test_param;
    }
}