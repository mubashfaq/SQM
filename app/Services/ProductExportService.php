<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;


class ProductExportService
{
    // Default export properties
    protected $outputDir = 'product_list';
    protected $fileName = 'product_list.csv';

    // Alternate export properties
    protected $alternateOutputDir = 'product_list';
    protected $alternateFileName = 'product_list_gm.csv';

    /**
     * Execute the default product export process.
     *
     * @return bool
     */
    public function exportProducts()
    {
        try {
            // Ensure default directory exists
            $this->ensureDirectoryExists();

            // Get products data
            $products = $this->getProducts();

            // Generate CSV content
            $csvContent = $this->generateCsvContent($products);

            // Save to file
            return $this->saveToFile($csvContent);
        } catch (\Exception $e) {
            Log::error('Product export failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Execute the alternate product export process.
     *
     * @return bool
     */
    public function exportAlternateProducts()
    {
        try {
            // Ensure alternate directory exists
            $this->ensureAlternateDirectoryExists();

            // Get products data
            $products = $this->getProducts();

            // Generate alternate CSV content (change headers/format as needed)
            $csvContent = $this->generateAlternateCsvContent($products);

            // Save to alternate file
            return $this->saveAlternateToFile($csvContent);
        } catch (\Exception $e) {
            Log::error('Alternate product export failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure the default output directory exists.
     */
    protected function ensureDirectoryExists()
    {
        $path = public_path($this->outputDir);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * Ensure the alternate output directory exists.
     */
    protected function ensureAlternateDirectoryExists()
    {
        $path = public_path($this->alternateOutputDir);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * Retrieve products from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getProducts()
    {
        $activeStatuses = [1];
        return Product::with('category')
            ->whereIn('active', $activeStatuses)
            ->whereNotNull('brand_id')
            ->where('brand_id', '!=', '')
            ->whereNotNull('category_id')
            ->where('category_id', '!=', '')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Generate CSV content for the default format.
     *
     * @param \Illuminate\Database\Eloquent\Collection $products
     * @return string
     */
    protected function generateCsvContent($products)
    {
        $csvData = [];
        $csvData[] = [
            'Product Name',
            'MPN',
            'SKU',
            'Product ID',
            'Short Description',
            'Category Name',
            'Brand',
            'Model',
            'Image Link',
            'Product URL',
            'Price',
            'Sale Price',
        ];

        foreach ($products as $product) {
            $encodedPermalink = rawurlencode($product->permalink);
            $fullUrl = 'https://autopartstation.com.au/product/' . $encodedPermalink;
            $imageLink = $product->featured_image
                ? 'https://admin.autopartstation.com.au/' . $product->featured_image
                : '';
                $rawPrice = $product->productMeta()->where('option', '_price')->first()->value ?? '';
                $price = is_numeric($rawPrice) ? number_format((float)$rawPrice, 2, '.', '') : '';
                $salePrice = $product->productMeta()->where('option', '_price')->first()->value ?? '';
            $categoryName = $product->category->title ?? '';
            $cleanText = strip_tags($product->short_description);
            $cleanText = html_entity_decode($cleanText, ENT_QUOTES | ENT_HTML5);
            $shortDescription = trim($cleanText) ?? '';
            $row = [
                $product->title ?? '',
                $product->sku,
                $product->sku ?? '',
                $product->id ?? '',
                $shortDescription,
                $categoryName,
                $product->brand ? $product->brand->title : '',
                $product->sku,
                $imageLink,
                $fullUrl,
                $price,
                $salePrice,
            ];

            $csvData[] = $row;
        }

        return $this->convertToCsv($csvData);
    }

    /**
     * Generate CSV content for the alternate format.
     *
     * @param \Illuminate\Database\Eloquent\Collection $products
     * @return string
     */
    protected function generateAlternateCsvContent($products)
    {
        $csvData = [];
        $csvData[] = [
            'id', 'title', 'description', 'availability', 'link', 'image_link', 'price', 'identifier_exists', 'gtin', 'mpn', 'brand', 'shipping_weight', 'shipping_currency'
        ];

        foreach ($products as $product) {
            $encodedPermalink = rawurlencode($product->permalink);
            $fullUrl = 'https://autopartstation.com.au/product/' . $encodedPermalink;
    
            $imageLink = $product->featured_image 
                ? 'https://admin.autopartstation.com.au/' . $product->featured_image 
                : '';
                $rawPrice = $product->productMeta()->where('option', '_price')->first()->value ?? '';
                $price = is_numeric($rawPrice) ? number_format((float)$rawPrice, 2, '.', '') : '';
                $cleanText = strip_tags($product->short_description);
            $cleanText = html_entity_decode($cleanText, ENT_QUOTES | ENT_HTML5);
            $shortDescription = trim($cleanText) ?? '';
            
            $row = [
                $product->sku ?? '',
                $product->title ?? '',
                $shortDescription ?? '',
                'in_stock',
                $fullUrl,
                $imageLink,
                $price . ' AUD',
                'No',  // Static value for identifier_exists
                $product->sku,  // Empty for GTIN (if not available)
                $product->sku,  // Empty for MPN (if not available)
                $product->brand ? $product->brand->title : '',  // Brand title or empty if no brand
                '0.5 kg',
                'A$'
            ];
    
            $csvData[] = $row;
        }

        return $this->convertToCsv($csvData);
    }

    /**
     * Convert a data array to a CSV-formatted string.
     *
     * @param array $data
     * @return string
     */
    protected function convertToCsv(array $data)
    {
        $output = fopen('php://temp', 'r+');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }

    /**
     * Save CSV content to the default file.
     *
     * @param string $content
     * @return bool
     */
    protected function saveToFile($content)
    {
        $filePath = public_path($this->outputDir . '/' . $this->fileName);
        return File::put($filePath, $content) !== false;
    }

    /**
     * Save CSV content to the alternate file.
     *
     * @param string $content
     * @return bool
     */
    protected function saveAlternateToFile($content)
    {
        $filePath = public_path($this->alternateOutputDir . '/' . $this->alternateFileName);
        return File::put($filePath, $content) !== false;
    }

    public function getOutputDir()
    {
        return $this->outputDir;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get the URL to the default generated CSV file.
     *
     * @return string
     */
    public function getFileUrl()
    {
        return url($this->outputDir . '/' . $this->fileName);
    }

    /**
     * Get the URL to the alternate generated CSV file.
     *
     * @return string
     */
    public function getAlternateFileUrl()
    {
        return url($this->alternateOutputDir . '/' . $this->alternateFileName);
    }

    /**
     * Get the file path for the alternate CSV file.
     *
     * @return string
     */
    public function getAlternateFilePath()
    {
        return public_path($this->alternateOutputDir . '/' . $this->alternateFileName);
    }
}
