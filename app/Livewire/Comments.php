<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Comment;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use App\Notifications\NewComment;
use Illuminate\Support\Facades\DB;

class Comments extends Component
{
    #[Locked]
    public int $postId;

    public ?int $parentId = null;

    public function render() : View
    {
        return view('livewire.comments', [
            'comments' => Comment::query()
                ->where('post_id', $this->postId)
                ->whereNull('parent_id')
                ->paginate(30),

            'commentsCount' => Comment::query()
                ->where('post_id', $this->postId)
                ->count(),
        ]);
    }

    #[On('comment.submitted')]
    public function store(?int $parentId = null, string $commentContent = '') : void
    {
        if (auth()->guest()) {
            abort(401);
        }

        $comment = Comment::query()->create([
            'post_id' => $this->postId,
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
            'content' => $commentContent,
        ]);

        if ('benjamincrozat' !== auth()->user()->github_login) {
            User::query()
                ->where('github_login', 'benjamincrozat')
                ->first()
                ?->notify(new NewComment($comment));
        }

        $this->reset('parentId');
    }

    public function delete(int $commentId) : void
    {
        if (auth()->guest()) {
            abort(401);
        }

        $comment = Comment::query()->find($commentId);

        if (auth()->user()->cannot('delete', $comment)) {
            abort(403);
        }

        if ($comment && auth()->user()->can('delete', $comment)) {
            DB::transaction(function () use ($comment) {
                $comment->delete();

                Comment::query()
                    ->where('parent_id', $comment->id)
                    ->delete();
            });
        }
    }
}
