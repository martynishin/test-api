<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\EventDestroyRequest;
use App\Http\Requests\EventReplaceRequest;
use App\Http\Requests\EventShowRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Requests\EventUpdateRequestInterface;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class EventController extends Controller
{
    /**
     * @param  BaseRequest  $request
     * @return AnonymousResourceCollection
     */
    public function index(BaseRequest $request): AnonymousResourceCollection
    {
        return EventResource::collection($request->organization()->events);
    }

    /**
     * @param  EventShowRequest  $request
     * @return EventResource
     */
    public function show(EventShowRequest $request): EventResource
    {
        $event = Event::query()->find($request->id);

        return new EventResource($event);
    }

    /**
     * @param  EventReplaceRequest  $request
     * @return EventResource
     * @throws Throwable
     */
    public function replace(EventReplaceRequest $request): EventResource
    {
        return $this->processUpdate($request);
    }

    /**
     * @param  EventUpdateRequest  $request
     * @return EventResource
     * @throws Throwable
     */
    public function update(EventUpdateRequest $request): EventResource
    {
        return $this->processUpdate($request);
    }

    /**
     * @param  EventUpdateRequestInterface  $request
     * @return EventResource
     * @throws Throwable
     */
    private function processUpdate(EventUpdateRequestInterface $request): EventResource
    {
        $event = Event::query()->find($request->id);

        // I don't use $request->validated() because I need to skip id
        $data = $request->safe()->only(['event_title', 'event_start_date', 'event_end_date']);

        $event->updateOrFail($data);

        return new EventResource($event);
    }

    /**
     * @param  EventDestroyRequest  $request
     * @return JsonResponse
     */
    public function destroy(EventDestroyRequest $request): JsonResponse
    {
        Event::query()->whereKey($request->id)->delete();

        return response()->json([
            'message' => 'Event successfully deleted.'
        ]);
    }
}
