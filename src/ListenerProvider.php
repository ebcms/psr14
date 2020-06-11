<?php declare (strict_types = 1);

namespace Ebcms;

use Fig\EventDispatcher\ParameterDeriverTrait;
use Psr\EventDispatcher\ListenerProviderInterface;
use SplPriorityQueue;

class ListenerProvider implements ListenerProviderInterface
{
    use ParameterDeriverTrait;
    private $listeners = [];

    public function getListenersForEvent(object $event): iterable
    {
        $queue = new SplPriorityQueue();
        foreach ($this->listeners as $listener) {
            if ($event instanceof $listener['type']) {
                $queue->insert($listener['listener'], $listener['priority']);
            }
        }
        return $queue;
    }

    public function listen(callable $listener, int $priority = 0): self
    {
        $type = $this->getParameterType($listener);
        $this->listeners[] = [
            'type' => $type,
            'listener' => $listener,
            'priority' => $priority,
        ];
        return $this;
    }
}
