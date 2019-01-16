<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $table = 'api_queue';

    protected $fillable = [
        'what',
        'tags'
    ];

    public static $rules = [
        'what' => 'required',
        'tags' => 'required'
    ];
}