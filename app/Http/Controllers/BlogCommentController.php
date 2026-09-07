<?php

namespace App\Http\Controllers;

use App\Models\RestaurantBlog;
use App\Models\RestaurantBlogComment;
use App\Models\RestaurantBlogReaction;
use App\Models\RestaurantBlogCommentReaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    /**
     * Store a new comment or nested reply on a blog post.
     */
    public function store(Request $request, RestaurantBlog $blog): RedirectResponse|JsonResponse
    {
        $isAuth = auth()->check();

        $rules = [
            'comment' => ['required', 'string', 'min:2', 'max:2000'],
            'parent_id' => ['nullable', 'exists:restaurant_blog_comments,id'],
        ];

        if (!$isAuth) {
            $rules['name'] = ['required', 'string', 'max:100'];
            $rules['email'] = ['required', 'email', 'max:150'];
        }

        $validated = $request->validate($rules);

        $name = $isAuth ? auth()->user()->name : trim($validated['name']);
        $email = $isAuth ? auth()->user()->email : trim($validated['email']);
        $userId = $isAuth ? auth()->id() : null;
        $parentId = !empty($validated['parent_id']) ? (int)$validated['parent_id'] : null;

        // If parent_id provided, verify it belongs to this blog
        if ($parentId) {
            $parentExists = RestaurantBlogComment::where('id', $parentId)
                ->where('restaurant_blog_id', $blog->id)
                ->exists();
            if (!$parentExists) {
                $parentId = null;
            }
        }

        $comment = RestaurantBlogComment::create([
            'restaurant_blog_id' => $blog->id,
            'user_id' => $userId,
            'parent_id' => $parentId,
            'name' => $name,
            'email' => $email,
            'comment' => $validated['comment'],
            'status' => 'approved',
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
        ]);

        $message = $parentId ? 'Your reply has been posted successfully!' : 'Your comment has been posted successfully!';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'comment' => $comment->load('user'),
            ]);
        }

        return redirect()->to(url()->previous() . '#comment-' . $comment->id)
            ->with('success', $message);
    }

    /**
     * Update an existing comment.
     */
    public function update(Request $request, RestaurantBlogComment $comment): RedirectResponse|JsonResponse
    {
        if (!$comment->isAuthoredByCurrentViewer()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            return back()->with('error', 'You are not authorized to edit this comment.');
        }

        $validated = $request->validate([
            'comment' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        $comment->update([
            'comment' => $validated['comment'],
        ]);

        $message = 'Comment updated successfully!';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'comment' => $comment,
            ]);
        }

        return redirect()->to(url()->previous() . '#comment-' . $comment->id)
            ->with('success', $message);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Request $request, RestaurantBlogComment $comment): RedirectResponse|JsonResponse
    {
        if (!$comment->isAuthoredByCurrentViewer()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            return back()->with('error', 'You are not authorized to delete this comment.');
        }

        $comment->delete();

        $message = 'Comment deleted successfully!';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->to(url()->previous() . '#commentsSection')
            ->with('success', $message);
    }

    /**
     * React (Like / Dislike) on a blog post.
     */
    public function reactBlog(Request $request, RestaurantBlog $blog): JsonResponse
    {
        $validated = $request->validate([
            'reaction_type' => ['required', 'in:like,dislike'],
        ]);

        $type = $validated['reaction_type'];
        $userId = auth()->id();
        $sessionId = session()->getId();
        $ip = $request->ip();

        $existing = RestaurantBlogReaction::where('restaurant_blog_id', $blog->id)
            ->where(function ($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();

        $userReaction = null;

        if ($existing) {
            if ($existing->reaction_type === $type) {
                // Remove reaction (toggle off)
                $existing->delete();
                $userReaction = null;
            } else {
                // Switch reaction (e.g. like -> dislike)
                $existing->update(['reaction_type' => $type]);
                $userReaction = $type;
            }
        } else {
            // New reaction
            RestaurantBlogReaction::create([
                'restaurant_blog_id' => $blog->id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ip_address' => $ip,
                'reaction_type' => $type,
            ]);
            $userReaction = $type;
        }

        // Recalculate counts
        $likesCount = RestaurantBlogReaction::where('restaurant_blog_id', $blog->id)->where('reaction_type', 'like')->count();
        $dislikesCount = RestaurantBlogReaction::where('restaurant_blog_id', $blog->id)->where('reaction_type', 'dislike')->count();

        $blog->update([
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);

        return response()->json([
            'success' => true,
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
            'user_reaction' => $userReaction,
        ]);
    }

    /**
     * React (Like / Dislike) on a comment.
     */
    public function reactComment(Request $request, RestaurantBlogComment $comment): JsonResponse
    {
        $validated = $request->validate([
            'reaction_type' => ['required', 'in:like,dislike'],
        ]);

        $type = $validated['reaction_type'];
        $userId = auth()->id();
        $sessionId = session()->getId();
        $ip = $request->ip();

        $existing = RestaurantBlogCommentReaction::where('comment_id', $comment->id)
            ->where(function ($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();

        $userReaction = null;

        if ($existing) {
            if ($existing->reaction_type === $type) {
                $existing->delete();
                $userReaction = null;
            } else {
                $existing->update(['reaction_type' => $type]);
                $userReaction = $type;
            }
        } else {
            RestaurantBlogCommentReaction::create([
                'comment_id' => $comment->id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ip_address' => $ip,
                'reaction_type' => $type,
            ]);
            $userReaction = $type;
        }

        $likesCount = RestaurantBlogCommentReaction::where('comment_id', $comment->id)->where('reaction_type', 'like')->count();
        $dislikesCount = RestaurantBlogCommentReaction::where('comment_id', $comment->id)->where('reaction_type', 'dislike')->count();

        $comment->update([
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);

        return response()->json([
            'success' => true,
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
            'user_reaction' => $userReaction,
        ]);
    }
}
