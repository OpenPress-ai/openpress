<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ImportService
{
    public function importJson(string $path): void
    {
        Log::info('Starting import from path: ' . $path);
        
        try {
            $contents = File::get($path);
            $json = json_decode($contents, true);
            Log::info('JSON decoded successfully. Data keys: ' . implode(', ', array_keys($json['data'])));
        } catch (\Exception $e) {
            Log::error('Error reading or decoding JSON: ' . $e->getMessage());
            throw $e;
        }

        DB::transaction(function () use ($json) {
            $this->importUsers($json['data']['users']);
            $this->importTags($json['data']['tags']);
            $this->importPosts($json['data']['posts'], $json['data']['posts_authors']);
            $this->attachTagsToPosts($json['data']['posts_tags'], $json['data']['tags']);
        });

        Log::info('Import completed successfully');
    }

    private function importUsers(array $users): void
    {
        Log::info('Importing users. Count: ' . count($users));
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
        Log::info('Users imported successfully');
    }

    private function importTags(array $tags): void
    {
        Log::info('Importing tags. Count: ' . count($tags));
        foreach ($tags as $tagData) {
            Tag::updateOrCreate(
                ['slug' => $tagData['slug']],
                [
                    'name' => $tagData['name'],
                ]
            );
        }
        Log::info('Tags imported successfully');
    }

    private function importPosts(array $posts, array $postsAuthors): void
    {
        Log::info('Importing posts. Count: ' . count($posts));
        $authorMap = collect($postsAuthors)->pluck('author_id', 'post_id')->toArray();

        foreach ($posts as $postData) {
            $authorId = $authorMap[$postData['id']] ?? null;
            
            if (!$authorId) {
                Log::warning("No author found for post ID: {$postData['id']}");
                continue;
            }

            $post = Post::updateOrCreate(
                ['wordpress_id' => $postData['id']],
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

            Log::info("Post created/updated", [
                'id' => $post->id,
                'wordpress_id' => $post->wordpress_id,
                'title' => $post->title,
                'slug' => $post->slug,
                'author_id' => $post->author_id
            ]);
        }
        Log::info('Posts imported successfully');
    }

    private function attachTagsToPosts(array $postsTags, array $tags): void
    {
        Log::info('Attaching tags to posts');
        $tagSlugMap = collect($tags)->pluck('slug', 'id')->toArray();

        $tagMap = collect($postsTags)->groupBy('post_id')->map(function ($tags) use ($tagSlugMap) {
            return collect($tags)->map(function ($tag) use ($tagSlugMap) {
                return $tagSlugMap[$tag['tag_id']] ?? null;
            })->filter()->toArray();
        })->toArray();

        foreach ($tagMap as $wordpressPostId => $tagSlugs) {
            $post = Post::where('wordpress_id', $wordpressPostId)->first();
            if ($post) {
                $tagIds = Tag::whereIn('slug', $tagSlugs)->pluck('id');
                $post->tags()->sync($tagIds);
                Log::info("Attached tags to post", [
                    'id' => $post->id,
                    'wordpress_id' => $post->wordpress_id,
                    'tag_slugs' => $tagSlugs,
                    'tag_ids' => $tagIds
                ]);
            } else {
                Log::warning("Post not found for WordPress ID: {$wordpressPostId} when attaching tags");
            }
        }
        Log::info('Tags attached to posts successfully');
    }
}