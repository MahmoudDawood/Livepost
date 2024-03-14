<?php

namespace App\Repositories;

use App\Models\User;
use App\Exceptions\GeneralJsonException;

class UserRepository extends BaseRepository {
    public function create(array $attributes) {
        $user = User::query()->create([
            'name' => data_get($attributes, 'name'),
            'email' => data_get($attributes, 'email'),
            'password' => data_get($attributes, 'password'),
        ]);
 
        throw_if(!$user, new GeneralJsonException('Failed to create user'));

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
            'password' => data_get($attributes, 'password') ?? $user->password,
        ]);

        throw_if(!$updated, new GeneralJsonException('Failed to update user'));

        return $user;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function forceDelete($user) {
        $deleted = $user->forceDelete();

        throw_if(!$deleted, new GeneralJsonException('Failed to delete user'));
    }

}