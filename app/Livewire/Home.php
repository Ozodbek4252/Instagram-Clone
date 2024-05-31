<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Home extends Component
{
    public $posts;
    public $canLoadMore = true;
    public $perPageIncrements = 5;
    public $perPage = 10;

    function mount()
    {
        $this->loadPosts();
    }

    public function render()
    {
        $suggestedUsers = User::limit(5)->get();

        return view('livewire.home', compact('suggestedUsers'));
    }

    public function toggleFollow(User $userToFollow)
    {
        abort_unless(auth()->check(), 401);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->toggleFollow($userToFollow);
    }

    function loadMore()
    {
        if (!$this->canLoadMore) {
            return null;
        }

        # Increment page
        $this->perPage += $this->perPageIncrements;

        # Load posts
        $this->loadPosts();
    }

    function loadPosts()
    {
        $this->posts = Post::with('comments.replies')->latest()->take($this->perPage)->get();

        $this->canLoadMore = $this->posts->count() >= $this->perPage;
    }

    #[On('closeModal')]
    function revertUrl()
    {
        $this->js("history.replaceState({}, '', '/')");
    }

    #[On('post-created')]
    function postCreated($id)
    {
        $post = Post::find($id);
        $this->posts = $this->posts->prepend($post);
    }
}
