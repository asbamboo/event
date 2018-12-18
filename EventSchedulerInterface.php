<?php
namespace asbamboo\event;

/**
 * 事件调度器接口
 * 在本模块内，事件调度器采用单例模式开发
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月1日
 */
interface EventSchedulerInterface
{
    /**
     * 获取事件调度器接口实例
     */
    public static function instance() : EventSchedulerInterface;

    /**
     * 当参数个数是2个或者3个，并且第2个参数是callable类型的时候 表示绑定事件，否则表示触发事件。
     * @param string $name
     * @param ...$args
     */
    public function on(string $name, ...$args) : bool;

    /**
     * 绑定一个事件
     * @param string $name
     * @param callable $call
     * @param int $priority 如果是null， 默认值从1开始递增，以DESC排序的顺序执行
     */
    public function bind(string $name, callable $call, int $priority = null) : bool;

    /**
     * 触发事件
     * @param string $name
     * @param string|array $args
     */
    public function trigger(string $name, $args) : bool;

    /**
     * 返回是否设置了名为$name的事件调度监听器。
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name) : bool;
}
