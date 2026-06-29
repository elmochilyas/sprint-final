<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Blueprint;
use App\Models\User;

class BlueprintController extends Controller
{
    public function index(): JsonResponse
    {
        $blueprints = auth()->user()->blueprints()->latest()->get();
        return response()->json($blueprints);
    }

    public function show(Blueprint $blueprint)
    {
        return response()->json($blueprint);
    }

    public function store(): JsonResponse
    {
        return response()->json(['message' => 'TODO']);
    }
}
