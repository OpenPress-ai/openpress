<?php

namespace Tests\Feature\PageBuilder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
    }

    public function test_admin_can_see_page_builder_in_admin_panel()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Page Builder');
    }

    public function test_admin_can_access_page_builder_section()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/page-builder');

        $response->assertStatus(200);
        $response->assertSee('Page Builder Dashboard');
    }

    public function test_admin_can_see_list_of_pages_in_page_builder()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/page-builder/pages');

        $response->assertStatus(200);
        $response->assertSee('Pages');
    }

    public function test_admin_can_create_new_page_with_page_builder()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/page-builder/pages/create');

        $response->assertStatus(200);
        $response->assertSee('Create New Page');
    }

    public function test_admin_can_edit_existing_page_with_page_builder()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Assume we have a page with ID 1
        $response = $this->actingAs($user)->get('/admin/page-builder/pages/1/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Page');
    }

    public function test_non_admin_cannot_access_page_builder_section()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/page-builder');

        $response->assertStatus(403);
    }
}