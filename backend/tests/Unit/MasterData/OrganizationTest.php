<?php

namespace Tests\Unit\MasterData;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Organization;

class OrganizationTest extends TestCase
{
    use WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */
      
     public function test_save_org_data(){
        $parent = Organization::inRandomOrder()->first();
        $response = $this->postJson("/api/organization", ["organization_name" => $this->faker->jobTitle, "organization_status" => 1, 'organization_parent' => $parent->organization_id]);
        $response->assertStatus(201);
    }
    public function test_index_org_data(){
        $response = $this->getJson("/api/organization");
        $response->assertStatus(200);
    }
    public function test_find_org_data(){
        $org = Organization::inRandomOrder()->first();
        $response = $this->getJson("/api/organization/".$org->organization_id);
        $response->assertStatus(200);
    }
    public function test_update_org_data(){
        $org = Organization::inRandomOrder()->first();
        $parent = Organization::inRandomOrder()->first();
        $response = $this->putJson("/api/organization/".$org->organization_id, ["organization_name" => $this->faker->jobTitle, 'organization_status' => 1, 'organization_parent' => $parent->organization_id]);
        $response->assertStatus(200);
    }
    public function test_delete_org_data(){
        $org = Organization::inRandomOrder()->first();
        $response = $this->deleteJson("/api/organization/".$org->organization_id);
        $response->assertStatus(200);
    }
}
