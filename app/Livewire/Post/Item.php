<?php

namespace App\Livewire\Post;

use Livewire\Component;

class Item extends Component
{
    public $post;
    public $body;

    public function render()
    {
        return view('livewire.post.item');
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
