<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function calculateSavings(Request $request)
{
    $minsanCodes = $request->input('minsanCodes');

    $results = DB::table('combined_pharma_data_table')
        ->select(
            'source_table',
            DB::raw('SUM(CAST(price_discounted AS DECIMAL(10,2))) as total_price'),
            DB::raw('COUNT(DISTINCT MINSAN_CODE) as product_count')
        )
        ->whereIn('MINSAN_CODE', $minsanCodes)
        ->groupBy('source_table')
        ->having('product_count', '=', count($minsanCodes)) // Verifica che la farmacia abbia tutti i prodotti
        ->orderBy('total_price', 'asc') // Ordina dal prezzo più basso
        ->get();

    if ($results->isEmpty()) {
        return response()->json([
            'message' => 'Nessuna farmacia ha tutti i prodotti richiesti',
            'savings' => []
        ], 404);
    }

    return response()->json(['savings' => $results]);
    }
}