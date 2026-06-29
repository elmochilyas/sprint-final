<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BlueprintController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'TODO']);
    }

    public function show(): JsonResponse
    {
        return response()->json(['message' => 'TODO']);
    }

    public function store(): JsonResponse
    {
        return response()->json(['message' => 'TODO']);
    }
}
