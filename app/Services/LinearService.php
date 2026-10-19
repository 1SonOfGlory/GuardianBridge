<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\DeadLetterQueue;
use Exception;

class LinearService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.linear.api_key', env('LINEAR_API_KEY'));
    }

    /**
     * Dispatch a Linear Issue asynchronously.
     */
    public function dispatchIssue(array $data): void
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.linear.app/graphql', [
                'query' => 'mutation IssueCreate($input: IssueCreateInput!) { issueCreate(input: $input) { success issue { id title } } }',
                'variables' => [
                    'input' => [
                        'teamId' => 'GUARDIAN-TEAM-ID', // Configurable in real implementation
                        'title' => $data['title'] ?? 'Automated Guardian Alert',
                        'description' => $data['description'] ?? 'No description provided.',
                    ]
                ]
            ]);

            if ($response->failed() || isset($response->json()['errors'])) {
                throw new Exception("Linear API Error: " . $response->body());
            }
            
        } catch (Exception $e) {
            Log::error("Failed to create Linear issue: " . $e->getMessage());
            
            // Queue to DLQ to ensure zero data loss
            DeadLetterQueue::create([
                'source' => 'LinearService',
                'payload' => json_encode($data),
                'error_reason' => $e->getMessage()
            ]);
        }
    }
}
