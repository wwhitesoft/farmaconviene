<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function calculateSavings(Request $request)
    {
        try {
            // Ottieni i dati dalla richiesta
            $items = $request->input('items', []);
            
            // Verifica che items sia un array valido
            if (!is_array($items) || empty($items)) {
                return response()->json([
                    'message' => 'Nessun prodotto nel carrello',
                    'savings' => []
                ], 200); // Uso 200 invece di 404 per evitare errori nella UI
            }
            
            // Estrai i codici MINSAN e crea un array associativo di quantità
            $minsanCodes = [];
            $quantities = [];
            
            foreach ($items as $item) {
                if (isset($item['sku'])) {
                    $sku = $item['sku'];
                    $minsanCodes[] = $sku;
                    $quantities[$sku] = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                }
            }
            
            // Se non ci sono codici MINSAN validi, restituisci un array vuoto
            if (empty($minsanCodes)) {
                return response()->json([
                    'message' => 'Codici MINSAN non validi',
                    'savings' => []
                ], 200);
            }
            
            // Ottieni tutte le farmacie disponibili
            $pharmacies = DB::table('combined_pharma_data_table')
                ->select('source_table')
                ->whereIn('MINSAN_CODE', $minsanCodes)
                ->distinct()
                ->get()
                ->pluck('source_table')
                ->toArray();
            
            // Se non ci sono farmacie, restituisci un array vuoto
            if (empty($pharmacies)) {
                return response()->json([
                    'message' => 'Nessuna farmacia trovata',
                    'savings' => []
                ], 200);
            }
            
            // Calcola i prezzi totali per ogni farmacia
            $results = [];
            
            foreach ($pharmacies as $pharmacy) {
                $totalPrice = 0;
                $productCount = 0;
                
                // Per ogni codice MINSAN, cerca i prezzi nella farmacia corrente
                foreach ($minsanCodes as $code) {
                    $quantity = $quantities[$code];
                    
                    // Cerca il prodotto nella farmacia
                    $product = DB::table('combined_pharma_data_table')
                        ->where('MINSAN_CODE', $code)
                        ->where('source_table', $pharmacy)
                        ->select('price_discounted', 'price_original')
                        ->first();
                    
                    if ($product) {
                        // Usa il prezzo scontato se disponibile, altrimenti il prezzo originale
                        $price = !empty($product->price_discounted) ? $product->price_discounted : $product->price_original;
                        
                        if ($price > 0) {
                            $totalPrice += $price * $quantity;
                            $productCount++;
                        }
                    }
                }
                
                // Aggiungi il risultato solo se almeno un prodotto è stato trovato
                if ($productCount > 0) {
                    $results[] = [
                        'source_table' => $pharmacy,
                        'total_price' => $totalPrice,
                        'product_count' => $productCount,
                        'total_products' => count($minsanCodes)
                    ];
                }
            }
            
            // Ordina i risultati per prezzo totale (crescente)
            usort($results, function($a, $b) {
                return $a['total_price'] <=> $b['total_price'];
            });
            
            return response()->json(['savings' => $results]);
            
        } catch (\Exception $e) {
            Log::error('Errore nel calcolo dei risparmi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'message' => 'Errore nel calcolo dei risparmi: ' . $e->getMessage(),
                'savings' => []
            ], 200); // Uso 200 invece di 500 per evitare errori nella UI
        }
    }
}