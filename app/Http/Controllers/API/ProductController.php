<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\ProductExportService;
use Illuminate\Http\Response;
use App\Models\ProductMeta;



class ProductController extends Controller
{

    public function index(Product $product, Request $request)
    {
        return $this->getLatestData($product, $request);
    }

    public function getProductById(Product $product, $permalink)
    {
        return $this->getProductBySku($product, $permalink);
    }


    // Todo code from Mohammad Salam
    public function shop(Request $request)
    {
        // Specify the number of items you want per page
        $perPage = 16;

        // Initialize the query
        $query = Product::query();

        // Check if 'category' parameter is present in the request
        if ($request->has('category')) {
            $categorySlug = $request->input('category');
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('categories.sku', $categorySlug)
                ->select('products.*');
        }

        // Check if 'child_category' parameter is present in the request
        if ($request->has('child_category')) {
            $childCategorySlug = $request->input('child_category');
            $query->join('child_categories', 'products.child_category', '=', 'child_categories.id')
                ->where('child_categories.child_sku', $childCategorySlug)
                ->select('products.*');
        }

        // Check if 'brand' parameter is present in the request
        if ($request->has('brand')) {
            $brandPermalink = $request->input('brand');
            $query->join('brands', 'products.brand_id', '=', 'brands.id')
                ->where('brands.permalink', $brandPermalink)
                ->select('products.*');
        }

        // Only retrieve products that have a featured_image
        $query->whereNotNull('products.featured_image');
        $query->where('products.active', 1);


        // Order the results by product ID in descending order
        $query->orderBy('products.id', 'desc');

        // Paginate the results
        $products = $query->paginate($perPage);

        // Customize the response structure if needed
        return response()->json([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
            'next_page_url' => $products->nextPageUrl(),
            'prev_page_url' => $products->previousPageUrl(),
            'discount' => $products
        ], 200);
    }

    public function searchProduct(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return $this->error('search is required', 400);
        }
        $searchWords = explode(' ', $search);
        $products = Product::where('active', 1)
            ->where(function ($q) use ($search, $searchWords) {
                $q->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('permalink', 'LIKE', '%' . $search . '%')
                        ->orWhere('sku', 'LIKE', '%' . $search . '%');
                })
                    ->orWhere(function ($q) use ($searchWords) {
                        foreach ($searchWords as $word) {
                            $q->where('title', 'LIKE', '%' . $word . '%');
                        }
                    });
            })
            ->get();

        return $this->success($products);
    }



    public function exportCsv(Request $request, ProductExportService $exportService)
    {

        $result = $exportService->exportProducts();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Product export completed successfully',
                'file_url' => $exportService->getFileUrl()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product export failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLatestFileUrl(ProductExportService $exportService)
    {
        $filePath = public_path($exportService->getOutputDir() . '/' . $exportService->getFileName());
        if (file_exists($filePath)) {
            return response()->download($filePath, null, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Product export file not found'
        ], 404);
    }

    public function exportGm(Request $request, ProductExportService $exportService)
    {
        $result = $exportService->exportAlternateProducts();

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Alternate product export completed successfully',
                'file_url' => $exportService->getAlternateFileUrl()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Alternate product export failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Alternate download endpoint
    public function getGmLatestFileUrl(ProductExportService $exportService)
    {
        $filePath = $exportService->getAlternateFilePath();
        if (file_exists($filePath)) {
            return response()->download($filePath, null, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT'
            ]);        }
        return response()->json([
            'success' => false,
            'message' => 'Alternate product export file not found'
        ], 404);
    }
    
    
    
    public function generateDescriptions()
    {
        // Get products with short descriptions less than 160 characters
        $products = Product::where(function($query) {
                // Look for common HTML tags in the short_description
                $query->whereRaw("short_description LIKE '%<%>%'")
                    ->orWhereRaw("short_description LIKE '%</%>%'")
                    ->orWhereRaw("short_description LIKE '%<strong>%'")
                    ->orWhereRaw("short_description LIKE '%<p>%'")
                    ->orWhereRaw("short_description LIKE '%<div>%'")
                    ->orWhereRaw("short_description LIKE '%<span>%'")
                    ->orWhereRaw("short_description LIKE '%<br%'")
                    ->orWhereRaw("short_description LIKE '%&nbsp;%'");
            })
            ->get(['id', 'title', 'sku', 'short_description']);
        $updated = 0;
        $errors = 0;
        dd($products);
        foreach ($products as $product) {
            try {
                // Get both short description and SEO description from OpenAI
                $descriptions = $this->getDescriptionsFromOpenAI($product->title, $product->sku);
                if ($descriptions && isset($descriptions['short_description']) && isset($descriptions['seo_description'])) {
                    // Update product short description
                    $product->short_description = $descriptions['short_description'];
                    $product->save();
                    
                    // Update or create SEO description meta
                    $seoDescriptionMeta = ProductMeta::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'option' => '_seo_description'
                        ],
                        [
                            'value' => $descriptions['seo_description']
                        ]
                    );
                    
                    $updated++;
                    
                    // Log the product that was updated
                    \Log::info('Product descriptions updated', [
                        'product_id' => $product->id,
                        'product_title' => $product->title,
                        'product_sku' => $product->sku,
                        'updated_short_description' => $descriptions['short_description'],
                        'updated_seo_description' => $descriptions['seo_description']
                    ]);
                }
            } catch (\Exception $e) {
                // Log error for each product that fails
                \Log::error('Error generating descriptions for product #' . $product->id . ': ' . $e->getMessage());
                $errors++;
            }
        }
        
        return response()->json([
            'success' => true,
            'total_products_processed' => count($products),
            'products_updated' => $updated,
            'errors' => $errors
        ]);
    }

/**
 * Get product descriptions from OpenAI API
 */
    private function getDescriptionsFromOpenAI($title, $sku)
    {
        $apiKey = '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';
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json'
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                'content' => 'You are a product description writer. Generate two descriptions for the product based on its title and SKU:
                1. A short_description that is **exactly 160 characters long**, engaging, descriptive, and highlights key features and benefits.
                2. A seo_description that is **less than or exactly 150 characters**, optimized for search engines, concise, and focused on key keywords for SEO.
                Use Australian English spelling and terminology.'
                ],
                [
                    'role' => 'user',
                    'content' => "Product Title: $title\nProduct SKU: $sku\n\nGenerate both a short product description (160 chars) and SEO description (150 chars). Format your response as JSON with keys 'short_description' and 'seo_description'."
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 350,
            'response_format' => ['type' => 'json_object']
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            $contentJson = $data['choices'][0]['message']['content'] ?? null;
            
            if ($contentJson) {
                try {
                    $descriptions = json_decode($contentJson, true);
                    return $descriptions;
                } catch (\Exception $e) {
                    \Log::error('Failed to parse OpenAI JSON response: ' . $e->getMessage());
                    return null;
                }
            }
        }
        
        \Log::error('OpenAI API Error: ' . $response->body());
        return null;
    }
    

   
}
