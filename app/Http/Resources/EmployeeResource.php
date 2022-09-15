<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'address' => $this->address,
            'country' => $this->country->name,
            'state' => $this->states->name,
            'city' => $this->cities->name,
            'zip_code' => $this->zip_code,
            'birthDate' => $this->birth_date,
            'dateHired' => $this->date_hired,
        ];
    }
}