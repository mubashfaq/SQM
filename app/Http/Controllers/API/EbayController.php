<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Traits\EbayTrait;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class EbayController extends Controller
{
    use EbayTrait;

    /**
     * Redirect to eBay for user consent.
     */
    public function redirectToEbayAuthorization()
    {
        $authUrl = $this->getAccessTokenUsingClientCredentials();
        dd($authUrl);
        return redirect($authUrl);
    }

    /**
     * Handle the callback from eBay after user grants consent.
     */
    public function handleEbayCallback(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response()->json(['error' => 'Authorization code not provided.'], 400);
        }

        $tokenData = $this->exchangeCodeForToken($code);

        if ($tokenData) {
            return response()->json(['message' => 'Token acquired successfully', 'data' => $tokenData]);
        }

        return response()->json(['message' => 'Failed to acquire token'], 400);
    }

    /**
     * List all products on eBay.
     */
    public function listAllProducts()
    {
        // Ensure access token is valid
        if (!$this->accessToken || $this->isAccessTokenExpired()) {
            $this->refreshAccessToken();
        }

        $products = Product::where('active', 1)->get();
        $results = [];

        foreach ($products as $product) {
            $price = $product->getMetaValue('_price');

            if (!$price) {
                \Log::error("Price not found for product ID: {$product->id}");
                continue; // Skip products without a price
            }

            $response = $this->createInventoryItem(
                $product->sku,
                $product->title,
                $price,
                $product->quantity,
                $product->short_description,
                $product->featured_image
            );

            $results[] = [
                'product' => $product->title,
                'status' => isset($response['error']) && $response['error'] ? 'Failed' : 'Success',
                'response' => $response,
            ];

            sleep(1); // Optional delay to prevent rate limits
        }

        return response()->json([
            'message' => 'Products processed',
            'results' => $results,
        ]);
    }

    /**
     * Check if the access token is expired.
     */
    private function isAccessTokenExpired()
    {
        // Implement logic to check if the token is expired, e.g., based on stored expiration time
        return false;
    }
}
