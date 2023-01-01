<?php

namespace Tests\Unit\MasterData;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Role;
class RoleTest extends TestCase
{
    use WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    
    public function test_save_role_data(){
        $response = $this->postJson("/api/role", ["role_name" => $this->faker->jobTitle, "description" => $this->faker->text, "is_active" => 1]);
        $response->assertStatus(200);
    }
    public function test_index_role_data(){
        $response = $this->getJson("/api/role");
        $response->assertStatus(200);
    }
    public function test_find_role_data(){
        $role = Role::inRandomOrder()->first();
        $response = $this->getJson("/api/role/".$role->role_id);
        $response->assertStatus(200);
    }
    public function test_update_role_data(){
        $role = Role::inRandomOrder()->first();
        $response = $this->putJson("/api/role/".$role->role_id, ["role_name" => $this->faker->jobTitle, 'description' => $this->faker->text]);
        $response->assertStatus(200);
    }
    public function test_delete_role_data(){
        $role = Role::inRandomOrder()->first();
        $response = $this->deleteJson("/api/role/".$role->role_id);
        $response->assertStatus(200);
    }
}
