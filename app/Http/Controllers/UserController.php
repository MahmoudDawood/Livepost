<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @group User Management
 *
 * APIs to manage the user resource.
 * */
class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * Gets a list of users.
     *
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 20;
        $users = User::query()->paginate($pageSize);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     * @bodyParam name string required Name of the user. Example: John Doe
     * @bodyParam email string required Email of the user. Example: doe@doe.com
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @param  \Illuminate\Http\Request $request
     * @return UserResource
     */
    public function store(Request $request, UserRepository $userRepository)
    {
        $user = $userRepository->create($request->only([
            'name',
            'email',
            'password'
        ]));

        return new UserResource($user);
    }

    /**
     * Display the specified user.
     *
     * @urlParam id int required User ID
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @param  \App\Models\User  $user
     * @return UserResource
     */
    public function show(User $user)
    {

        return new UserResource($user);
   }

    /**
     * Update the specified user in storage.
     * @bodyParam name string Name of the user. Example: John Doe
     * @bodyParam email string Email of the user. Example: doe@doe.com
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\User  $user
     * @return UserResource | JsonResponse
     */
    public function update(Request $request, User $user, UserRepository $userRepository)
    {
        $updatedUser = $userRepository->update($user, $request->only([
            'name',
            'email',
            'password'
        ]));

        return new UserResource($updatedUser);
   }

    /**
     * Remove the specified user from storage.
     * @response 200 {
        "data": "success"
     * }
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user, UserRepository $userRepository)
    {
        $userRepository->forceDelete($user);

        return new JsonResponse([
            'data' => 'Deleted'
        ]);
    }
}
