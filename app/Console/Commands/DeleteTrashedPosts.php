<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;

class DeleteTrashedPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:delete-trashed-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete posts that have been soft deleted for more than 30 days';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $posts = Post::onlyTrashed()->where('deleted_at', '<', Carbon::now()->subDays(30))->get();

        foreach ($posts as $post) {
            $post->forceDelete();
        }
    }
}
