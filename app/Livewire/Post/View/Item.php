<?php

namespace App\Livewire\Post\View;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class Item extends Component
{
    public Post $post;
    public $body;
    public $parent_id = null;

    public function render()
    {
        $comments = $this->post->comments()->whereDoesntHave('parent')->get();
        return view('livewire.post.view.item', compact('comments'));
    }

    public function togglePostLike()
    {
        abort_unless(auth()->check(), 403);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->toggleLike($this->post);
    }

    function setParent(Comment $comment)
    {
        $this->parent_id = $comment->id;
        $this->body = '@' . $comment->user->name . ' ';
    }

    public function addComment()
    {
        $this->validate([
            'body' => 'required'
        ]);

        $this->post->comments()->create([
            'body' => $this->body,
            'parent_id' => $this->parent_id,
            'user_id' => auth()->id()
        ]);

        $this->body = '';
    }
}
