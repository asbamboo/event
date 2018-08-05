<?php
namespace asbamboo\event;

use asbamboo\helper\traits\SingletonClassTrait;

/**
 * event 事件注册器
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月1日
 */
class EventListener implements EventListenerInterface
{
    use SingletonClassTrait;

    protected $registers;

    /**
     * {@inheritDoc}
     * @see \asbamboo\event\EventListenerInterface::set()
     */
    public function set(string $listener_name, string $class, string $method, array $arguments = [], int $priority = null) : EventListenerInterface
    {
        $this->registers[$listener_name][]  = [
            'class'                         => $class,
            'method'                        => $method,
            'arguments'                     => $arguments,
            'priority'                      => $priority,
        ];
        
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \asbamboo\event\EventListenerInterface::has()
     */
    public function has(string $listener_name) : bool
    {
        return isset($this->registers[$listener_name]);
    }

    /**
     * {@inheritDoc}
     * @see \asbamboo\event\EventListenerInterface::get()
     */
    public function get(string $listener_name) : array
    {
        return $this->registers[$listener_name] ?? [];
    }

    /**
     * {@inheritDoc}
     * @see \asbamboo\event\EventListenerInterface::bind()
     */
    public function bind(string $listener_name) : bool
    {
        foreach($this->registers[$listener_name] AS $key => $register){
            $object     = new $register['class'](...$register['arguments']);
            $method     = $register['method'];
            $priority   = $register['priority'];
            EventScheduler::instance()->bind($listener_name, [$object, $method], $priority);
            unset($this->registers[$listener_name][$key ]);
        }
        unset($this->registers[$listener_name]);
        return true;
    }
}
