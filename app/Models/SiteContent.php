<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model {
    protected $fillable = [
        'id',
        'key',
        'title',
        'image',
        'value'];
}