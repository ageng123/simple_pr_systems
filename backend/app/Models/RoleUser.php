<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleUser extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "role_users";
    protected $primaryKey = "ru_id";
    protected $guarded = [];
}
