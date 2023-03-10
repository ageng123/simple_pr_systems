<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "organizations";
    protected $primaryKey = "organization_id";
    protected $guarded = [];
    public function parent(){
        return $this->belongsTo(Organization::class, 'organization_parent', 'organization_id');
    }
}
