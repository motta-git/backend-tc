<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Server;

class ServerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_servers(): void
    {
        $server = Server::factory()->create();

        $response = $this->getJson('/api/servers');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'id' => $server->id,
            'host' => $server->host,
            'ip' => $server->ip,
        ]);
    }

    public function test_can_create_a_server(): void
    {
        Storage::fake('public');

        $serverData = [
            'host' => 'new-server.com',
            'ip' => '192.168.1.50',
            'description' => 'A brand new server.',
            'image' => UploadedFile::fake()->image('server.jpg', 300, 300)
        ];

        $response = $this->postJson('/api/servers', $serverData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('servers', ['host' => 'new-server.com']);
    }

    public function test_validation_fails_if_host_is_missing(): void
    {
        $serverData = [
            'ip' => '192.168.1.50',
            'description' => 'A server without a host.',
            'image' => UploadedFile::fake()->image('server.jpg', 300, 300)
        ];

        $response = $this->postJson('/api/servers', $serverData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('host');
    }
}