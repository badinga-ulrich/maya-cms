<?php

namespace Hhxsv5\SSE;

class Event
{
    /**
     * @var string The event ID to set the EventSource object's last event ID value.
     */
    protected $id;

    /**
     * @var array A string identifying the type of event described. If this is specified, an event will be dispatched on the browser to the listener for the specified event name; the website source code should use addEventListener() to listen for named events. The onmessage handler is called if no event name is specified for a message.
     */
    protected $events;

    /**
     * @var string A string identifying the type of event described. If this is specified, an event will be dispatched on the browser to the listener for the specified event name; the website source code should use addEventListener() to listen for named events. The onmessage handler is called if no event name is specified for a message.
     */
    protected $event;

    /**
     * @var string The initial $event
     */
    protected $initialEvent;

    /**
     * @var string The data field for the message. When the EventSource receives multiple consecutive lines that begin with data:, it will concatenate them, inserting a newline character between each one. Trailing newlines are removed.
     */
    protected $data;

    /**
     * @var int The reconnection time to use when attempting to send the event. This must be an integer, specifying the reconnection time in milliseconds. If a non-integer value is specified, the field is ignored.
     */
    protected $retry;

    /**
     * @var string This is just a comment, since it starts with a colon character. As mentioned previously, this can be useful as a keep-alive if messages may not be sent regularly.
     */
    protected $comment;

    /**
     * @var callable The callback to get event data. Throw a {@see StopSSEException} to stop the execution, browser will retry after {@see $retry}
     */
    protected $callback;

    /**
     * Event constructor.
     * @param callable $callback {@see Event::$callback}
     * @param string $event {@see Event::$event}
     * @param int $retry {@see Event::$retry}
     */
    public function __construct(callable $callback, $event = '', $retry = 5000)
    {
        $this->callback = $callback;
        $this->id = '';
        $this->data = '';
        $this->initialEvent = $this->event = $event;
        $this->retry = $retry;
        $this->comment = '';
        $this->events = [];
    }

    /**
     * Fill the event data & id
     * @return $this
     * @throws StopSSEException
     */
    public function fill()
    {
        $this->event = $this->initialEvent;
        $result = call_user_func($this->callback);
        if ($result === false) {
            $this->id = '';
            $this->data = '';
            $this->comment = 'no data';
        } else if (isset($result['events'])) {
            $this->events = is_array($result['events']) ? $result['events'] : [];
        }else {
            if (isset($result['event'])) {
                $this->event = $result['event'];
            }
            $this->id = isset($result['id']) ? $result['id'] : str_replace('.', '', uniqid('', true));
            $this->data = isset($result['data']) ? $result['data'] : $result;
            $this->comment = isset($result['comment']) ? $result['comment'] : '';
        }
        return $this;
    }
    private function showData($data){
        return implode("\ndata: ",explode("\n",json_encode($data,JSON_PRETTY_PRINT)));
    }

    public function __toString()
    {
        if(count($this->events)){
            $ret = [];
            foreach ($this->events as $event) {
                $e = [];
                if (isset($event['comment']) && $event['comment'] !== '') {
                    $this->comment = $event['comment'];
                    $e[] = sprintf(': %s', $event['comment']);
                }
                if (isset($event['id']) && $event['id'] !== '') {
                    $this->id = $event['id'];
                    $e[] = sprintf('id: %s', $event['id']);
                }
                $e[] = sprintf('time: %s', time());

                if (isset($event['retry']) && $event['retry'] > 0) {
                    $this->retry = $event['retry'];
                    $e[] = sprintf('retry: %s', $event['retry']);
                }
                if (isset($event['event']) && $event['event'] !== '') {
                    $this->event = $event['event'];
                    $e[] = sprintf('event: %s', $event['event']);
                }
                if (isset($event['data']) && $event['data'] !== '') {
                    $this->data = $event['data'];
                    $e[] = sprintf('data: %s', $this->showData($event['data']));
                }
                $ret[] = implode("\n", $e);    
            }
            $this->events = []; // flush
            return implode("\n\n", $ret)."\n\n";
        } else if($this->id == ''){
            return "";
        } else {
            $e = [];
            if ($this->comment !== '') {
                $e[] = sprintf(': %s', $this->comment);
            }
            if ($this->id !== '') {
                $e[] = sprintf('id: %s', $this->id);
            }
            // add process time
            $e[] = sprintf('time: %s', time());
            if ($this->retry > 0) {
                $e[] = sprintf('retry: %s', $this->retry);
            }
            if ($this->event !== '') {
                $e[] = sprintf('event: %s', $this->event);
            }
            if ($this->data !== '') {
                $e[] = sprintf('data: %s', $this->showData($this->data));
            }
            return implode("\n", $e) . "\n\n";
        }
    }
}
