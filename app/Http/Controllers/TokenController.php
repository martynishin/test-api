<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenStoreRequest;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;

class TokenController extends Controller
{
    /**
     * @param  TokenService  $service
     */
    public function __construct(private readonly TokenService $service)
    {
    }

    /**
     * @param  TokenStoreRequest  $request
     * @return JsonResponse
     */
    public function store(TokenStoreRequest $request): JsonResponse
    {
        $token = $this->service->issueToken($request->input('email'), $request->input('password'));

        return response()->json(['token' => $token]);
    }
}
