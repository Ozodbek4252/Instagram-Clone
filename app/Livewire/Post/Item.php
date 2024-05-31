<?php

namespace App\Livewire\Post;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Comment;
use App\Models\Post;

class Item extends Component
{
    public Post $post;
    public $body;

    public function render()
    {
        return view('livewire.post.item');
    }

    public function togglePostLike()
    {
        abort_unless(auth()->check(), 401);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->toggleLike($this->post);
    }

    public function toggleCommentLike(Comment $comment)
    {
        abort_unless(auth()->check(), 401);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->toggleLike($comment);
    }

    #[On('post-liked')]
    function postLiked($id)
    {
        $this->render();
    }

    #[On('comment-liked')]
    function commentLiked($id)
    {
        $this->render();
    }

    public function addComment()
    {
        $this->validate([
            'body' => 'required'
        ]);

        $this->post->comments()->create([
            'body' => $this->body,
            'user_id' => auth()->id()
        ]);

        $this->body = '';
    }
}
