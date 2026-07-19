<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResumeRequest;
use App\Http\Requests\UpdateResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Models\Resume;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $resumes = Resume::query()
            ->when(request('language'), fn ($query, $language) => $query->where('language', $language))
            ->when(request('version'), fn ($query, $version) => $query->where('version', $version))
            ->latest()
            ->paginate();

        return ResumeResource::collection($resumes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResumeRequest $request): JsonResponse
    {
        $resume = Resume::query()->create($request->validated());

        return (new ResumeResource($resume))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Resume $resume): ResumeResource
    {
        return new ResumeResource($resume);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResumeRequest $request, Resume $resume): ResumeResource
    {
        $resume->update($request->validated());

        return new ResumeResource($resume->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resume $resume): Response
    {
        $resume->delete();

        return response()->noContent();
    }
}
