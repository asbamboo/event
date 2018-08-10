<?php
namespace asbamboo\event;

use asbamboo\event\exception\EventSortListenerException;
use asbamboo\helper\traits\SingletonClassTrait;

/**
 * 事件调度器
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月1日
 */
class EventScheduler implements EventSchedulerInterface
{
    use SingletonClassTrait;

    /**
     * 创建并获取类的实例
     * 为了更好的支持ide，这里重写了SingletonClassTrait中的instance方法，区别在于声明的返回值类型
     * 
     * @return EventSchedulerInterface
     */
    public static function instance() : EventSchedulerInterface
    {
        if(! static::$instance){
            static::$instance    = new static();
        }
        return static::$instance;
    }
    
    /**
     * PROPAGATION_STOPED 当一个listener执行后，返回 PROPAGATION_STOPED 会阻止该事件传递给其他监听器listener。
     * @var bool
     */
    const PROPAGATION_STOPED    = false;

    /**
     * $listeners   = [['call'=> callable, 'priority' => priority], ['call'=> callable, 'priority' => priority], ...]
     * @var array
     */
    protected $listeners        = [];

    private $listeners_sorted   = [];

    /**
     * {@inheritDoc}
     * @see \asbamboo\event\EventSchedulerInterface::on()
     */
    public function on(string $name, ...$args) : bool
    {
        if(isset( $args[0] ) && is_callable( $args[0] )){
            return static::bind($name, $args[0], $args[1] ?? null);
        }else{
            return call_user_func_array([$this, 'trigger'], [$name, $args]);
        }
        return false;
    }

    /**
     * {@inheritDoc}
     * @see \asbamboo\event\EventSchedulerInterface::bind()
     */
    public function bind(string $name, callable $call, int $priority = null) : bool
    {
        $this->listeners_sorted[$name]  = false;

        if(is_null( $priority )){
            $max_priority   = isset( $this->listeners[$name] ) ? max(array_column($this->listeners[$name], 'priority')) : 0;
            $priority       = $max_priority + 1;
        }

        $this->listeners[$name][]   = [
            'call'                  => $call,
            'priority'              => $priority,
        ];
        
        return true;
    }

    /**
     * {@inheritDoc}
     * @see \asbamboo\event\EventSchedulerInterface::trigger()
     */
    public function trigger(string $name, $args) : bool
    {
        if(EventListener::instance()->has($name)){
            EventListener::instance()->bind($name);
        }

        if(!isset($this->listeners[$name])){
            return false;
        }

        $this->listenerSort($name);
        foreach($this->listeners[$name] AS $key => $listener){
            if(call_user_func_array($listener['call'], (array)$args) === static::PROPAGATION_STOPED){
                break;
            }
        }
        
        return true;
    }

    /**
     * @param string $name
     * @throws EventSortListenerException
     */
    private function listenerSort(string $name) : void
    {
        if(!empty($this->listeners_sorted[$name])){
            return;
        }

        $prioritys  = [];
        foreach($this->listeners[$name] AS $key => $listener){
            $prioritys[$key]    = $listener['priority'];
        }

        if(!array_multisort($prioritys, SORT_DESC, $this->listeners[$name])){
            throw new EventSortListenerException('事件监听器，系统排序发生异常。');
        }

        $this->listeners_sorted[$name]  = true;
    }
}
