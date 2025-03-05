<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use App\Services\SubscriberService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriberResource;
use App\DTOs\Subscriber\CreateSubscriberDTO;
use App\DTOs\Subscriber\SubscriberFilterDTO;
use App\DTOs\Subscriber\UpdateSubscriberDTO;
use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubscriberController extends Controller
{
    use JsonResponses;
    public function __construct(public SubscriberService $subscriberService)
    {}

    /**
     * List all subscribers
     */
    public function index(Request $request): JsonResponse
    {
        $filterDTO = SubscriberFilterDTO::fromRequest($request);
        $subscribers = $this->subscriberService->getAllSubscribers($filterDTO);
        return $this->success(
            $subscribers, 
            "Subscribers retrieved successfully.",
        );
    }

    /**
     * Store a newly created subscribers in storage.
     */
    public function store(StoreSubscriberRequest $request): JsonResponse
    {
        $subscriberDTO = CreateSubscriberDTO::fromRequest($request);
        $subscriber = $this->subscriberService->createSubscriber($subscriberDTO);

        return $this->success(
            new SubscriberResource($subscriber),
            "Subscriber created successfully.",
            201,
        );
    }


    /**
     * Update the specified subscribers.
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber): JsonResponse
    {
        $subscriberDTO = UpdateSubscriberDTO::fromRequest($request);
        $updatedSubscriber = $this->subscriberService->updateSubscriber($subscriber, $subscriberDTO);

        return $this->success(new SubscriberResource($updatedSubscriber), "Subscriber updated successfully.");
    }


    /**
     * Display the specified subscribers.
     * @throws ModelNotFoundException
     */
    public function show(Subscriber $subscriber): JsonResponse
    {
        $subscriber->load('fieldValues');

        return $this->success(new SubscriberResource($subscriber), "Subscriber retrieved successfully.");
    }

    /**
     * Remove the specified subscribers.
     */
    public function destroy(Subscriber $subscriber): JsonResponse
    {
        $this->subscriberService->deleteSubscriber($subscriber);
        return $this->success(
            NULL,
            "Subscriber deleted successfully.",
            204
        );
    }
}
