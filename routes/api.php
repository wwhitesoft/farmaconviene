<?php
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/calculate-savings', [ApiController::class, 'calculateSavings'])->name('shop.api.calculate.savings');
Route::post('/check-minsan-codes', function(Request $request) {
    $minsanCodes = $request->input('minsan_codes', []);
    
    if (empty($minsanCodes)) {
        return response()->json([
            'error' => 'No MINSAN codes provided'
        ], 400);
    }

    $results = DB::table('combined_pharma_data_table')
        ->whereIn('minsan', $minsanCodes)
        ->get();

    return response()->json([
        'found_codes' => $results
    ]);
});