<?php

namespace Modules\Core\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
//            'user' => UserResource::make($this->whenLoaded('user')),
//            'user' => new UserResource($this->whenLoaded('user')),
            'parsed_body' => $this->parsed_body,
            $this->merge(Arr::except(parent::toArray($request), [
                'user_id',
                'created_at',
                'updated_at',
                'deleted_at'
            ]))

//            'id' => $this->id,
//            'user_id' => $this->user_id,
//            'user' => new UserResource($this->whenLoaded('user')),
//            'area' => $this->area,
//            'type' => $this->type,
//            'message' => $this->message,
//            'parsed_body' => $this->parsed_body,
//            'enabled' => $this->enabled,
//            'starts_at' => $this->starts_at,
//            'ends_at' => $this->ends_at,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
        ];
    }
}
