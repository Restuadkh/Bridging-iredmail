<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forwarding extends Model
{
    use HasFactory;

    protected $table = 'forwardings';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'id',
        'address',
        'forwarding',
        'domain',
        'dest_domain',
        'is_maillist',
        'is_list',
        'is_forwarding',
        'is_alias',
        'active',
    ];
    public $timestamps = false;

}
