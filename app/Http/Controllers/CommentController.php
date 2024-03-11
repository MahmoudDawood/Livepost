<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $pageSize = $request->pageSize ?? 5;
        $comments = Comment::query()->paginate($pageSize);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return CommentResource
     */
    public function store(Request $request)
    {
        $comment = Comment::query()->create([
            'body' => $request->body
        ]);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Comment $comment
     * @return CommentResource | JsonResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $updated = $comment->update([
            'body' => $request->body ?? $comment->body,

        ]);

        if(!$updated) {
            return new JsonResponse([
                'error' => 'Failed to update resource'
            ], 400);
        }

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $deleted = $comment->forceDelete();

        if(!$deleted) {
            return new JsonResponse([
                'error' => 'Failed to delete resource'
            ], 400);
        }

        return new JsonResponse([
            'data' => 'Deleted'
        ]);
    }
}
