<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlueprintRequest;
use App\Http\Resources\BlueprintResource;
use App\Models\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BlueprintController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $blueprints = auth()->user()->blueprints()->latest()->get();

        return BlueprintResource::collection($blueprints);
    }

    public function show(Blueprint $blueprint): BlueprintResource
    {
        return new BlueprintResource($blueprint);
    }

    public function store(StoreBlueprintRequest $request): JsonResponse
    {
        $blueprint = auth()->user()->blueprints()->create($request->validated());

        return (new BlueprintResource($blueprint))
            ->response()
            ->setStatusCode(201);
    }
}