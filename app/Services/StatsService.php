<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;

class StatsService
{
    public function getStatistics()
    {
        $userCount = User::count();

        $postCount = Post::count();

        $usersWithoutPosts = User::doesntHave('posts')->count();

        return [
            'total_users' => $userCount,
            'total_posts' => $postCount,
            'users_with_zero_posts' => $usersWithoutPosts,
        ];
    }
}
