<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use App\Events\Models\User\UserCreated;

use App\Events\Models\User\UserDeleted;
use App\Events\Models\User\UserUpdated;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $uri = '/api/v1/users/';

    public function test_index() {
        $users = User::factory(10)->create();
        $userIds = $users->map(function($user) {
            return $user->id;
        });

        $response = $this->json('get', $this->uri);
        
        $data = $response->assertStatus(200);
        $data = $response->json('data');

        collect($data)->each(function($user) use($userIds) {
            $this->assertTrue(in_array($user['id'], $userIds->toArray()));
        });
    }

    public function test_show() {
        $dummyUser = User::factory()->create();

        $response = $this->json('get',  $this->uri . $dummyUser->id);
        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertSame(data_get($data, 'id'), $dummyUser->id, 'Response ID does not match model id.');
    }

    public function test_create() {
        Event::fake();
        $dummyUser = User::factory()->make();

        $response = $this->json('post', $this->uri, $dummyUser->toArray());
        $response->assertStatus(201);
        Event::assertDispatched(UserCreated::class);
        
        $data = $response->json('data');
        $fillable = $dummyUser->getFillable();

        $result = collect($data)->only($fillable);

        collect($result)->each(function($value, $key) use($dummyUser) {
            $this->assertSame(data_get($dummyUser, $key), $value, 'Created user fillable is not the same');
        });
    }

    public function test_update() {
        Event::fake();
        $dummyUser = User::factory()->create();
        $tempUser = User::factory()->make();

        $fillables = (new User())->getFillable();
        $filteredFillables = collect($fillables)->reject(function($element) {
            return $element === 'password';
        });

        $filteredFillables->each(function($toUpdate) use($dummyUser, $tempUser) {
            $response = $this->json('patch',  $this->uri . $dummyUser->id, [
                $toUpdate => data_get($tempUser, $toUpdate)
            ]);

            $response->assertStatus(200);
            Event::assertDispatched(UserUpdated::class);

            $this->assertSame(data_get($dummyUser->refresh(), $toUpdate), data_get($tempUser, $toUpdate), 'Failed to update model');
        });

    }
    
    public function test_delete() {
        Event::fake();
        $dummyUser = User::factory()->create();

        // Request User delete
        $response = $this->json('delete', $this->uri . $dummyUser->id);
        $response->assertStatus(200);
        Event::assertDispatched(UserDeleted::class);

        // Assert User doesn't exist
        $this->expectException(ModelNotFoundException::class);
        User::query()->findOrFail($dummyUser->id);
    }
}
