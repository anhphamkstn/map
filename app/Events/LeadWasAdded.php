<?php namespace App\Events;
/**
 * Created by PhpStorm.
 * User: JonnyNguyen
 * Date: 13/07/2016
 * Time: 14:07
 */

namespace App\Events;
use App\Events\Event;
use App\Lead;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class LeadWasAdded extends Event
{
    use SerializesModels;

    public $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

}