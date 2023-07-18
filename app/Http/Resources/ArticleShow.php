<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleShow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'author' => $this->user->name,
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at->format("d M Y"),
            'updated_at' => $this->updated_at->format("d M Y"),
            'comments' => $this->comments,
        ];
    }
}
