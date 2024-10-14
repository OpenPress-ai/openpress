<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportService
{
    public function importJson(string $path): void
    {
        $json = json_decode(file_get_contents($path), true);

        DB::transaction(function () use ($json) {
            $this->importUsers($json['data']['users']);
            $this->importTags($json['data']['tags']);
            $this->importPosts($json['data']['posts'], $json['data']['posts_authors']);
            $this->attachTagsToPosts($json['data']['posts_tags']);
        });
    }

    private function importUsers(array $users): void
    {
        foreach ($users as $userData) {
            User::updateOrCreate(
                ['id' => $userData['id']],
                [
                    'slug' => $userData['slug'],
                    'bio' => $userData['bio'],
                    'website' => $userData['website'],
                    'email' => $userData['email'],
                    'name' => $userData['name'],
                    'profile_image' => $userData['profile_image'],
                    'created_at' => $userData['created_at'],
                ]
            );
        }
    }

    private function importTags(array $tags): void
    {
        foreach ($tags as $tagData) {
            Tag::updateOrCreate(
                ['id' => $tagData['id']],
                [
                    'name' => $tagData['name'],
                    'slug' => $tagData['slug'],
                ]
            );
        }
    }

    private function importPosts(array $posts, array $postsAuthors): void
    {
        $authorMap = collect($postsAuthors)->pluck('author_id', 'post_id')->toArray();

        foreach ($posts as $postData) {
            $authorId = $authorMap[$postData['id']] ?? null;
            
            if (!$authorId) {
                Log::warning("No author found for post ID: {$postData['id']}");
                continue;
            }

            Post::updateOrCreate(
                ['id' => $postData['id']],
                [
                    'title' => $postData['title'],
                    'slug' => $postData['slug'],
                    'mobiledoc' => $postData['mobiledoc'],
                    'feature_image' => $postData['feature_image'],
                    'feature_image_alt' => $postData['feature_image_alt'],
                    'feature_image_caption' => $postData['feature_image_caption'],
                    'featured' => $postData['featured'],
                    'type' => $postData['type'],
                    'status' => $postData['status'],
                    'meta_title' => $postData['meta_title'],
                    'meta_description' => $postData['meta_description'],
                    'created_at' => $postData['created_at'],
                    'updated_at' => $postData['updated_at'],
                    'published_at' => $postData['published_at'],
                    'author_id' => $authorId,
                ]
            );
        }
    }

    private function attachTagsToPosts(array $postsTags): void
    {
        foreach ($postsTags as $postTag) {
            $post = Post::find($postTag['post_id']);
            $tag = Tag::find($postTag['tag_id']);

            if ($post && $tag) {
                $post->tags()->syncWithoutDetaching([$tag->id]);
            }
        }
    }
}