<?php

namespace App\Http\Resources;

use App\DTOs\User\UserDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return UserDTO::fromModel($this->resource)->toArray();
    }
}
