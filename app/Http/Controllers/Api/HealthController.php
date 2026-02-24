<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    public function show(): JsonResponse
    {
        try {
            DB::connection()->getPdo();
        } catch (Throwable) {
            return response()->json(['status' => 'error'], 500);
        }

        return response()->json(
            data: [
                'message' => 'ok',
                'services' => [
                    'database' => 'up',
                ],
            ],
            status: 200,
        );
    }
}
