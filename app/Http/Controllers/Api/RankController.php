<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RankResource;
use App\Models\Rank;
use Illuminate\Http\JsonResponse;

class RankController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->success(RankResource::collection(Rank::ordered()->get()));
    }
}
