<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Blueprint;

class BlueprintController extends Controller
{
    public function index(): JsonResponse
    {
        $blueprints = Blueprint::latest()->get();
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
