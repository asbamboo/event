<?php
namespace asbamboo\event;

/**
 * Event事件注册器接口
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月1日
 */
interface EventListenerInterface
{
    /**
     * $arguments 作为 $class 实例 __construct方法参数，$method事件被触发时调用的方法
     * 注册器的listener被事件调度器使用时实例化。
     * @param string $class
     * @param string $method
     * @param array $arguments
     */
    public function set(string $listener_name, string $class, string $method, array $arguments = [], int $priority = null) : self;

    /**
     *
     * @param string $listener_name
     */
    public function has(string $listener_name) : bool;

    /**
     *
     * @param string $listener_name
     * @return array
     */
    public function get(string $listener_name) : array;

    /**
     * 将事件注册到时间调度器
     */
    public function bind(string $listener_name) : bool;
}
