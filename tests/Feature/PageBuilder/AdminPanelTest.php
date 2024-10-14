<?php

namespace Tests\Feature\PageBuilder;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_page_builder()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)->get('/admin/page-builder');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_page_builder()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/page-builder');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_page_with_page_builder()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)->post('/admin/pages', [
            'title' => 'New Test Page',
            'slug' => 'new-test-page',
            'content' => json_encode([
                ['type' => 'text', 'content' => 'This is a test page.']
            ])
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('pages', ['slug' => 'new-test-page']);
    }

    public function test_admin_can_edit_existing_page_with_page_builder()
    {
        $user = User::factory()->create(['is_admin' => true]);

        // Create a page
        $page = Page::create([
            'title' => 'Existing Test Page',
            'slug' => 'existing-test-page',
            'content' => json_encode([
                ['type' => 'text', 'content' => 'This is an existing test page.']
            ])
        ]);

        $response = $this->actingAs($user)->get("/admin/pages/{$page->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Existing Test Page');
    }

    public function test_admin_can_delete_page()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $page = Page::factory()->create();

        $response = $this->actingAs($user)->delete("/admin/pages/{$page->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }
}