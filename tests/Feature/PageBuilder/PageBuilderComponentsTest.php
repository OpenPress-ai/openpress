<?php

namespace Tests\Feature\PageBuilder;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class PageBuilderComponentsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_page_builder_can_load_required_assets()
    {
        $response = $this->actingAs($this->admin)->get(route('page-builder.create'));

        $response->assertStatus(200);
        $response->assertSee('Page Builder');
        // Add assertions for required assets (CSS, JS) here
    }

    public function test_page_builder_can_load_available_components()
    {
        $response = $this->actingAs($this->admin)->get(route('page-builder.create'));

        $response->assertStatus(200);
        $response->assertSee('Text Block');
        $response->assertSee('Image');
        $response->assertSee('Button');
        // Add more component assertions as needed
    }

    public function test_page_builder_can_create_a_basic_page_structure()
    {
        $response = $this->actingAs($this->admin)->post(route('page-builder.store'), [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => json_encode([
                [
                    'type' => 'text',
                    'content' => 'This is a test page content.'
                ],
                [
                    'type' => 'image',
                    'src' => 'https://example.com/image.jpg',
                    'alt' => 'Test Image'
                ]
            ])
        ]);
    
        $response->assertStatus(302);
        $this->assertDatabaseHas('pages', ['slug' => 'test-page']);
    }

    // Add more tests for editing, updating, and deleting pages
}