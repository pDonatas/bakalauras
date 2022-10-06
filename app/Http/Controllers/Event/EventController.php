<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use _PHPStan_acbb55bae\Nette\NotImplementedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function index(): Response
    {
        throw new NotImplementedException();
    }

    public function create(): Response
    {
        throw new NotImplementedException();
    }

    public function store(StoreEventRequest $request): Response
    {
        throw new NotImplementedException();
    }

    public function show(Event $event): Response
    {
        throw new NotImplementedException();
    }

    public function edit(Event $event): Response
    {
        throw new NotImplementedException();
    }

    public function update(UpdateEventRequest $request, Event $event): Response
    {
        throw new NotImplementedException();
    }

    public function destroy(Event $event): Response
    {
        throw new NotImplementedException();
    }
}
