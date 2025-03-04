<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Subscriber\CreateSubscriberDTO;
use App\DTOs\Subscriber\SubscriberFilterDTO;
use App\DTOs\Subscriber\UpdateSubscriberDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Models\Subscriber;
use App\Services\SubscriberService;
use App\Traits\JsonResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        return $this->success($subscribers->toArray(), "Subscribers retrieved successfully.");
    }

    /**
     * Store a newly created subscriber in storage.
     */
    public function store(StoreSubscriberRequest $request): JsonResponse
    {
        $subscriberDTO = CreateSubscriberDTO::fromRequest($request);
        $subscriber = $this->subscriberService->createSubscriber($subscriberDTO);

        return $this->success(
            $subscriber->toArray(),
            "Subscriber created successfully.",
            201,
        );
    }


    /**
     * Update the specified subscriber.
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber): JsonResponse
    {
        $subscriberDTO = UpdateSubscriberDTO::fromRequest($request);
        $updatedSubscriber = $this->subscriberService->updateSubscriber($subscriber, $subscriberDTO);

        return $this->success($updatedSubscriber->toArray(), "Subscriber updated successfully.");
    }


    /**
     * Display the specified subscriber.
     * @throws ModelNotFoundException
     */
    public function show(Subscriber $subscriber): JsonResponse
    {
        $subscriber->load('fieldValues');

        return $this->success($subscriber->toArray(), "Subscriber retrieved successfully.");
    }

    /**
     * Remove the specified subscriber.
     */
    public function destroy(Subscriber $subscriber): JsonResponse
    {
        $this->subscriberService->deleteSubscriber($subscriber);
        return $this->success(
            [],
            "Subscriber deleted successfully.",
            204
        );
    }
}
