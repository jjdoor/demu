<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Business extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'status'=>$this->status,
            'updated_at'=>$this->updated_at,
            'created_at'=>$this->created_at,
        ];
    }
}
