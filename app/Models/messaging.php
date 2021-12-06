<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class messaging extends Model
{
    use HasFactory;
    protected $table ='messaging';
    protected $fillable = ['id', 'dealer_id', 'owner_id', 'item_id', 'message', 'sent_by', 'created_at'];
   
    public $timestamps = false;
}
