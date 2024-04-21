<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;


/**
 * @group Post Management
 * APIs to manage post.
*/
class PostController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * Gets a list of posts.
     *
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * @param  \Illuminate\Http\Request $request
     * @return  ResourceCollection
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 20;
        $posts = Post::query()->paginate($pageSize);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created post in storage.
     * @bodyParam title string required Title of the post. Example: Amazing Post
     * @bodyParam body string[] required Body of the post. Example: ["This post is super beautiful"]
     * @bodyParam user_ids int[] required The author ids of the post. Example: [1, 2]
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * @param  PostStoreRequest $request
     * @return PostResource
     */
    public function store(PostStoreRequest $request, PostRepository $postRepository)
    {
        $post = $postRepository->create($request->only([
            'title',
            'body',
            'user_ids'
        ]));

        return new PostResource($post);
    }

    /**
     * Display the specified post.
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * @param  \App\Models\Post  $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified post in storage.
     * @bodyParam title string required Title of the post. Example: Amazing Post
     * @bodyParam body string[] required Body of the post. Example: ["This post is super beautiful"]
     * @bodyParam user_ids int[] required The author ids of the post. Example: [1, 2]
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Post $post
     * @return PostResource | JsonResponse
     */
    public function update(Request $request, Post $post, PostRepository $postRepository)
    {
        $post = $postRepository->update($post, $request->only([
            'title',
            'body'
        ]));

        return new PostResource($post);
    }

    /**
      * Remove the specified post from storage.
     * @response 200 {
        "data": "success"
     * }
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post, PostRepository $postRepository)
    {
        $postRepository->forceDelete($post);

        return new JsonResponse([
            "data" => "Deleted"
        ]);
    }
}
