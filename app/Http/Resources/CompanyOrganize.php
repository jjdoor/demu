<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyOrganize extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id'=>$this->id,
          'name'=>$this->name,
            "parent_id"=>$this->parent_id,
            "status"=>$this->status,
            "created_at"=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}
