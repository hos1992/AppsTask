<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all department employees
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get this department manager ( it will be restricted to one manager )
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function manager()
    {
        return $this->hasOne(User::class)->where('section_id', NULL);
    }

    /**
     * Get all department sections
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
