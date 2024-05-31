<?php

namespace App\Livewire\Post;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;

class Create extends ModalComponent
{
    use WithFileUploads;

    public $media = [];
    public $description;
    public $location;
    public $hide_like_view = false;
    public $allow_commenting = false;

    public function render()
    {
        return view('livewire.post.create');
    }
    
    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    public function submit()
    {
        DB::beginTransaction();
        $this->validate([
            'media.*' => 'required|file|max:100000|mimes:jpg,jpeg,png,gif,mp4,mov,avi,wmv,flv,webm,ogg',
            'allow_commenting' => 'boolean',
            'hide_like_view' => 'boolean',
        ]);

        # Determine if reel or post
        $type = $this->getPostType($this->media);

        # Create post
        $post = Post::create([
            'user_id' => auth()->user()->id,
            'description' => $this->description,
            'location' => $this->location,
            'hide_like_view' => $this->hide_like_view,
            'allow_commenting' => $this->allow_commenting,
            'type' => $type,
        ]);

        # Upload media
        foreach ($this->media as $key => $media) {
            $mime = $this->getMime($media);
            $path = $media->store('media', 'public');
            $url = url(Storage::url($path));

            Media::create([
                'url' => $url,
                'mime' => $mime,
                'mediable_id' => $post->id,
                'mediable_type' => Post::class,
            ]);

            $this->reset();
            $this->dispatch('close');

            $this->dispatch('post-created', $post->id);
        }
        DB::commit();
    }

    private function getMime($media): string
    {
        $mimeType = $media->getMimeType();
        $needle = 'video';

        if (str(Str::lower($mimeType))->contains(Str::lower($needle))) {
            return 'video';
        } else {
            return 'image';
        }
    }

    private function getPostType($media): string
    {
        $mimeType = $media[0]->getMimeType();
        $needle = 'video';

        if (str(Str::lower($mimeType))->contains(Str::lower($needle))) {
            return 'reel';
        } else {
            return 'post';
        }
    }
}
