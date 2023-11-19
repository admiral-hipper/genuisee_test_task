<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class ApiTestCase extends TestCase
{
    use RefreshDatabase, MakesGraphQLRequests;

    protected string $apiToken;

    public function setUp(): void
    {
        parent::setUp();
        $this->apiToken = $this->generateTestApiKey();
    }

    protected function generateTestApiKey(): string
    {
        $user = User::factory(['name' => 'Test GraphQL User'])->create();

        $plain_text_key = Str::random(40);
        $user->tokens()->create([
            'name' => 'Fixed Token',
            'token' => hash('sha256', $plain_text_key),
            'abilities' => ['*'],
        ]);

        return $plain_text_key;
    }
}
