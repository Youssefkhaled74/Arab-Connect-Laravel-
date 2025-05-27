<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BranchNotificationEvent
{
    use Dispatchable, SerializesModels;

    public $branches;

    /**
     * Create a new event instance...
     *
     * @param \Illuminate\Support\Collection $branches
     */
    public function __construct($branches)
    {
        $this->branches = $branches;
    }
}
