<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\On;

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
        abort_unless(auth()->check(), 403);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->toggleLike($this->post);

        $this->dispatch('post-liked', $this->post->id);
    }

    #[On('post-liked')]
    function postCreated($id)
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
