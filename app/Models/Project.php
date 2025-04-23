<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    use HasFactory;
    protected $fillable = ['name', 'reporting_manager_id', 'active'];

    public function reportingInCharge() {
        return $this->belongsTo(User::class, 'reporting_manager_id');
    }
}
