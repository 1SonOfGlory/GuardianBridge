<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostHogService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.posthog.api_key', env('POSTHOG_API_KEY'));
    }

    /**
     * Track an anonymized event using PostHog.
     */
    public function captureEvent(string $distinctId, string $event, array $properties = []): void
    {
        try {
            Http::post('https://app.posthog.com/capture/', [
                'api_key' => $this->apiKey,
                'event' => $event,
                'distinct_id' => $distinctId,
                'properties' => $properties
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to capture PostHog event: " . $e->getMessage());
            // Fail silently. Telemetry is non-critical path.
        }
    }
}
