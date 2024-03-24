<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\GeneralJsonException;

class UserRepositoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create()
    {
        // Goal
        // Replicate env
        $repository = $this->app->make(UserRepository::class);

        // Source of truth
        $randomNum = rand(1, 1000000);
        $payload = [
            'name' => 'username',
            'email' => "$randomNum@user.com",
            'password' => 'userPassword'
        ];

        // Compare results
        $created = $repository->create($payload);
        $this->assertSame($payload['name'], $created->name, 'Created user name is not matching');
        $this->assertSame($payload['email'], $created->email, 'Created user email is not matching');
    }

    public function test_update() {
        // Replicate env
        $repository = $this->app->make(UserRepository::class);
        $user = User::factory(1)->create()->first();

        // Source of truth

        $randomNum = rand(1, 1000000);
        $payload = [
            'name' => 'username',
            'email' => "$randomNum@user.com",
        ];

        // Compare results
        $updated = $repository->update($user, $payload);
        $this->assertSame($payload['name'], $updated->name, 'Updated user name is not matching');
        $this->assertSame($payload['email'], $updated->email, 'Updated user email is not matching');
   }

    public function test_delete() {
        // Replicate env
        $repository = $this->app->make(UserRepository::class);
        $dummyUser = User::factory(1)->create()->first();

        // Compare results
        $deleted = $repository->forceDelete($dummyUser);
        $found = User::query()->find($dummyUser->id);

        $this->assertSame(null, $found, 'Deleted user is not correct');
    }

    public function test_delete_exception_for_non_existing_user() {
        // Replicate env
        $repository = $this->app->make(UserRepository::class);
        $dummyUser = User::factory(1)->make()->first();

        // Compare results
        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummyUser);
    }
}
