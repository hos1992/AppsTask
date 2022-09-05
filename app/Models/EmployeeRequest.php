<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['statusTxt'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return string|void
     */
    public function getStatusTxtAttribute()
    {
        switch ($this->status) {
            case 0:
                return 'pending';
                break;
            case 1:
                return 'Waiting for hr manager approval';
                break;
            case 2:
                return 'approved by hr';
                break;
            case 3:
                return 'rejected';
                break;
        }
    }
}
