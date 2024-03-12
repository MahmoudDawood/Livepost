<?php

namespace App\Repositories;

use App\Models\User;
use BaseRepository;

class UserRepository extends BaseRepository {
    public function create(array $attributes) {
        $user = User::query()->create([
            'name' => data_get($attributes, 'name'),
            'email' => data_get($attributes, 'email'),
            'password' => data_get($attributes, 'password'),
        ]);

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

        if(!$updated) {
            throw new \Exception('Failed to updated user');
        }

        return $user;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function forceDelete($user) {
        $deleted = $user->forceDelete();

        if(!$deleted) {
            return new \Exception('Failed to delete user');
        }
    
        return $deleted;
    }

}