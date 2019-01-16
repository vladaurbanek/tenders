<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Post extends Resource
{
    public function toArray($request)
    {
        return [
            'what' => $this->resource['what'],
            'tags' => $this->resource['tags'],
            'timestamp' => $this->resource['timestamp'],
        ];
    }
}
