<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlueprintResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'ton' => $this->ton,
            'max_hashtags' => $this->max_hashtags,
            'max_caracteres' => $this->max_caracteres,
        ];
    }
}