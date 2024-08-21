<?php

namespace App\Services\Message;

use App\DTO\MessageDTO;
use App\Events\MessageStoreEvent;
use App\Models\Country;
use App\Models\Message;

class MessageService
{
    public function index()
    {
        return Message::all();
    }

    public function store(Country $country, MessageDTO $dto)
    {
        $message = $country->messages()->create($dto->toArray());
        broadcast(new MessageStoreEvent($message));
        return $message;
    }
}
