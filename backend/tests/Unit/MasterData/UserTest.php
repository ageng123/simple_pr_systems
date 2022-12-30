<?php

namespace Tests\Unit\MasterData;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
class UserTest extends TestCase
{
    use WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_index_without_query(){
        $response = $this->get("/api/users");
        $response->assertStatus(200);
    }
    public function test_store_user(){
        $response = $this->postJson("/api/users", ["name" => $this->faker->name, "email" => $this->faker->email, "password" => "12345678", "c_password" => "12345678", "user_status" => '1', "nip" => $this->faker->numerify("############"), "phone_number" => $this->faker->e164PhoneNumber]);
        $response->assertStatus(201);
    }
    public function test_find_users(){
        $user = User::inRandomOrder()->first();
        $response = $this->get("/api/users/".$user->uuid);
        $response->assertStatus(200);
    }
    public function test_find_users_when_user_not_found(){
        $user = User::inRandomOrder()->first();
        $response = $this->get("/api/users/".date("U").$user->uuid);
        $response->assertStatus(500);
    }
    public function test_update_users_profile(){
        $user = User::inRandomOrder()->first();
        $response = $this->putJson("/api/users/".$user->uuid, ["name" => $this->faker->name, "email" => $this->faker->email,"user_status" => '1', "nip" => $this->faker->numerify("############"), "phone_number" => $this->faker->e164PhoneNumber]);
        $response->assertStatus(200);
    }
    public function test_update_users_password(){
        $user = User::inRandomOrder()->first();
        $response = $this->putJson("/api/users/".$user->uuid, ["name" => $this->faker->name, "email" => $this->faker->email, "password" => "password", "c_password" => "password", "user_status" => '1', "nip" => $this->faker->numerify("############"), "phone_number" => $this->faker->e164PhoneNumber]);
        $response->assertStatus(200);
    }
    public function test_delete_user(){
        $user = User::inRandomOrder()->first();
        $response = $this->deleteJson("/api/users/".$user->uuid);
        $response->assertStatus(200);
    }
}
