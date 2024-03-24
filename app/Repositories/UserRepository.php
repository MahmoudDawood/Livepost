<?php

namespace App\Repositories;

use App\Models\User;
use App\Events\Models\User\UserUpdated;
use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserDeleted;
use App\Exceptions\GeneralJsonException;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository {
    public function create(array $attributes) {
        $user = User::query()->create([
            'name' => data_get($attributes, 'name'),
            'email' => data_get($attributes, 'email'),
            'password' => password_hash(data_get($attributes, 'password'), PASSWORD_DEFAULT),
        ]);
 
        throw_if(!$user, new GeneralJsonException('Failed to create user'));
        event(new UserCreated($user));

        return $user;
    }

    /**
     * @param User $user
     * @param array $attributes
     * @return mixed
    */
    public function update($user, array $attributes) {
        $updated = $user->update([
            'name' => data_get($attributes, 'name') ?? $user->name,
            'email' => data_get($attributes, 'email') ?? $user->email,
            'password' => Hash::make(data_get($attributes, 'password')),
        ]);

        throw_if(!$updated, new GeneralJsonException('Failed to update user'));
        event(new UserUpdated($user));

        return $user;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function forceDelete($user) {
        $deleted = $user->forceDelete();

        throw_if(!$deleted, new GeneralJsonException('Failed to delete user'));
        event(new UserDeleted($user));
    }

}