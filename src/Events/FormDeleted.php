<?php

declare(strict_types=1);

namespace Cortex\Forms\Events;

use Cortex\Forms\Models\Form;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FormDeleted implements ShouldBroadcast
{
    use InteractsWithSockets;
    use Dispatchable;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public $broadcastQueue = 'events';

    /**
     * The model instance passed to this event.
     *
     * @var \Cortex\Forms\Models\Form
     */
    public Form $model;

    /**
     * Create a new event instance.
     *
     * @param \Cortex\Forms\Models\Form $form
     */
    public function __construct(Form $form)
    {
        $this->model = $form->withoutRelations();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('cortex.forms.forms.index'),
            new PrivateChannel("cortex.forms.forms.{$this->model->getRouteKey()}"),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'form.deleted';
    }
}
