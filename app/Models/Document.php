<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Document extends Model
{
    use Searchable, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'category',
        'content',
        'file',
        'thumbnail',
        'user_id',
    ];

    protected $keyType = 'string'; // karena id UUID
    public $incrementing = false;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'content' => $this->content,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->timestamp,
        ];
    }
}
