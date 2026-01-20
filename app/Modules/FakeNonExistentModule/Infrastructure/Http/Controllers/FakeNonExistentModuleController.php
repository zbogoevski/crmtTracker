<?php

declare(strict_types=1);

namespace App\Modules\FakeNonExistentModule\Infrastructure\Http\Controllers;

use App\Modules\Core\Support\ApiResponse;
use App\Modules\Core\Traits\SwaggerTrait;
use App\Modules\FakeNonExistentModule\Infrastructure\Http\Requests\CreateFakeNonExistentModuleRequest;
use App\Modules\FakeNonExistentModule\Infrastructure\Http\Requests\UpdateFakeNonExistentModuleRequest;
use App\Modules\FakeNonExistentModule\Infrastructure\Http\Resources\FakeNonExistentModuleResource;
use App\Modules\FakeNonExistentModule\Application\DTO\FakeNonExistentModuleDTO;
use App\Modules\FakeNonExistentModule\Application\Actions\CreateFakeNonExistentModuleAction;
use App\Modules\FakeNonExistentModule\Application\Actions\UpdateFakeNonExistentModuleAction;
use App\Modules\FakeNonExistentModule\Application\Actions\DeleteFakeNonExistentModuleAction;
use App\Modules\FakeNonExistentModule\Application\Actions\GetAllFakeNonExistentModuleAction;
use App\Modules\FakeNonExistentModule\Application\Actions\GetByIdFakeNonExistentModuleAction;
use App\Modules\Core\Exceptions\CreateException;
use App\Modules\Core\Exceptions\DeleteException;
use App\Modules\Core\Exceptions\UpdateException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class FakeNonExistentModuleController extends Controller
{
    use SwaggerTrait;
    
    /**
     * FakeNonExistentModule Controller
     * 
     * All routes are protected with auth:sanctum middleware and Laravel default rate limiting:
     * - GET operations: 120 requests per minute
     * - POST/PUT operations: 20 requests per hour  
     * - DELETE operations: 5 requests per hour
     */
    /**
     * Get a paginated list of fakenonexistentmodules.
     *
     * @param GetAllFakeNonExistentModuleAction $action Action to retrieve all fakenonexistentmodules
     * @return JsonResponse Paginated list of fakenonexistentmodules
     * 
     * @OA\Get(
     *     path="/api/v1/fakenonexistentmodules",
     *     summary="List fakenonexistentmodules",
     *     description="Get paginated list of fakenonexistentmodules",
     *     tags={"FakeNonExistentModules"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FakeNonExistentModules retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="FakeNonExistentModules retrieved successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="meta", type="object"),
     *             @OA\Property(property="links", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index(GetAllFakeNonExistentModuleAction $action): JsonResponse
    {
        $fakenonexistentmodules = $action->execute((int) request()->get('per_page', 15));
        return ApiResponse::paginated($fakenonexistentmodules, 'FakeNonExistentModules retrieved successfully', FakeNonExistentModuleResource::collection($fakenonexistentmodules->items()));
    }

    /**
     * Get a specific fakenonexistentmodule by ID.
     *
     * @param int|string $id The fakenonexistentmodule ID (supports integer IDs, ULIDs, and UUIDs)
     * @param GetByIdFakeNonExistentModuleAction $action Action to retrieve fakenonexistentmodule by ID
     * @return JsonResponse fakenonexistentmodule details
     * 
     * @OA\Get(
     *     path="/api/v1/fakenonexistentmodules/{id}",
     *     summary="Get fakenonexistentmodule by ID",
     *     description="Get specific fakenonexistentmodule information by ID",
     *     tags={"FakeNonExistentModules"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="FakeNonExistentModule ID (supports integer IDs, ULIDs, and UUIDs)",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FakeNonExistentModule retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="FakeNonExistentModule retrieved successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="FakeNonExistentModule not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(int|string $id, GetByIdFakeNonExistentModuleAction $action): JsonResponse
    {
        return ApiResponse::success(new FakeNonExistentModuleResource($action->execute($id)), 'FakeNonExistentModule retrieved successfully');
    }

    /**
     * Create a new fakenonexistentmodule.
     *
     * @param CreateFakeNonExistentModuleRequest $request Validated request containing fakenonexistentmodule data
     * @param CreateFakeNonExistentModuleAction $action Action to create fakenonexistentmodule
     * @return JsonResponse Created fakenonexistentmodule details
     * @throws CreateException If fakenonexistentmodule creation fails
     * 
     * @OA\Post(
     *     path="/api/v1/fakenonexistentmodules",
     *     summary="Create fakenonexistentmodule",
     *     description="Create a new fakenonexistentmodule",
     *     tags={"FakeNonExistentModules"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Example fakenonexistentmodule")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="FakeNonExistentModule created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="FakeNonExistentModule created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function store(CreateFakeNonExistentModuleRequest $request, CreateFakeNonExistentModuleAction $action): JsonResponse
    {
        return ApiResponse::created(new FakeNonExistentModuleResource($action->execute(FakeNonExistentModuleDTO::fromRequest($request))), 'FakeNonExistentModule created successfully');
    }

    /**
     * Update an existing fakenonexistentmodule.
     *
     * @param int|string $id The fakenonexistentmodule ID (supports integer IDs, ULIDs, and UUIDs)
     * @param UpdateFakeNonExistentModuleRequest $request Validated request containing updated fakenonexistentmodule data
     * @param UpdateFakeNonExistentModuleAction $action Action to update fakenonexistentmodule
     * @return JsonResponse Updated fakenonexistentmodule details
     * @throws UpdateException If fakenonexistentmodule update fails
     * 
     * @OA\Put(
     *     path="/api/v1/fakenonexistentmodules/{id}",
     *     summary="Update fakenonexistentmodule",
     *     description="Update fakenonexistentmodule information",
     *     tags={"FakeNonExistentModules"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="FakeNonExistentModule ID (supports integer IDs, ULIDs, and UUIDs)",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated fakenonexistentmodule")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FakeNonExistentModule updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="FakeNonExistentModule updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="FakeNonExistentModule not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function update(int|string $id, UpdateFakeNonExistentModuleRequest $request, UpdateFakeNonExistentModuleAction $action): JsonResponse
    {
        return ApiResponse::success(new FakeNonExistentModuleResource($action->execute($id, FakeNonExistentModuleDTO::fromRequest($request))), 'FakeNonExistentModule updated successfully');
    }

    /**
     * Delete a fakenonexistentmodule.
     *
     * @param int|string $id The fakenonexistentmodule ID (supports integer IDs, ULIDs, and UUIDs)
     * @param DeleteFakeNonExistentModuleAction $action Action to delete fakenonexistentmodule
     * @return JsonResponse Success message
     * @throws DeleteException If fakenonexistentmodule deletion fails
     * 
     * @OA\Delete(
     *     path="/api/v1/fakenonexistentmodules/{id}",
     *     summary="Delete fakenonexistentmodule",
     *     description="Delete a fakenonexistentmodule",
     *     tags={"FakeNonExistentModules"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="FakeNonExistentModule ID (supports integer IDs, ULIDs, and UUIDs)",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FakeNonExistentModule deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="FakeNonExistentModule deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="FakeNonExistentModule not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(int|string $id, DeleteFakeNonExistentModuleAction $action): JsonResponse
    {
        $action->execute($id);
        return ApiResponse::success(null, 'FakeNonExistentModule deleted successfully');
    }
}
