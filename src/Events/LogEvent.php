<?php
/**
 * User: lzh
 * Date: 2020/9/7
 * Time: 10:23
 */

namespace CMM\RBAC\Events;

class LogEvent
{
    /**
     * @var string
     */
    public $desc;

    /**
     * @var array
     */
    public $original;

    /**
     * @var array
     */
    public $changes;

    /**
     * LogEvent constructor.
     * @param $desc
     * @param $original
     * @param $changes
     */
    public function __construct($desc, $original, $changes)
    {
        $this->desc = $desc;
        $this->original = $original;
        $this->changes = $changes;
    }
}
