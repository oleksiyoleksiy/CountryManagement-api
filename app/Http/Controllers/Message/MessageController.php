<?php

namespace App\Http\Controllers\Message;

use App\DTO\MessageDTO;
use App\Models\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Country;
use App\Services\Message\MessageService;

class MessageController extends Controller
{
    public function __construct(private MessageService $service) {}


    public function index(Country $country)
    {
        return MessageResource::collection($this->service->index());
    }


    public function store(Country $country, MessageRequest $request)
    {
        $data = $request->validated();

        $dto = new MessageDTO(...$data);

        return MessageResource::make($this->service->store($country, $dto));
    }
}
