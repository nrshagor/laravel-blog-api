<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'body' => $this->body,
                'user_id' => $this->user_id,
                'post_id' => $this->post_id,
                'created_at' => $this->created_at->toDateTimeString(),
                'updated_at' => $this->updated_at->toDateTimeString(),
            ],
        ];
    }
}
