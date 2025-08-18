<?php

namespace App\Services;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ChildCategory;

class SitemapService
{
    /**
     * Configuration constants
     */
    private const PRODUCTS_PER_PAGE = 16;
    private const CACHE_DURATION = 60 * 24; // 24 hours
    private const FRONTEND_URL = 'https://sydneyqualitymeats.com.au';

    /**
     * Generate sitemap with error handling and logging
     * 
     * @return bool
     */
    public function generate(): bool
    {
        try {
            // Create sitemap instance
            $sitemap = Sitemap::create();
            $this->addStaticPages($sitemap);
            $this->addDynamicPages($sitemap);
            $this->saveSitemap($sitemap);
            Log::info('Sitemap generated successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Sitemap generation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Add static pages from configuration
     * 
     * @param Sitemap $sitemap
     */
    private function addStaticPages(Sitemap $sitemap): void
    {
        $staticPages = config('sitemap.static_pages', []);
        
        foreach ($staticPages as $url => $config) {
            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate(now())
                    ->setChangeFrequency($config['frequency'] ?? 'weekly')
                    ->setPriority($config['priority'] ?? 0.5)
            );
        }
    }

    /**
     * Add all dynamic pages
     * 
     * @param Sitemap $sitemap
     */
    private function addDynamicPages(Sitemap $sitemap): void
    {
        Cache::remember('sitemap_dynamic_pages', self::CACHE_DURATION, function () use ($sitemap) {
            $this->addProductPages($sitemap);
            $this->addCategoryPages($sitemap);
            $this->addSubcategoryPages($sitemap);
            $this->addBrandPages($sitemap);
        });
    }

    /**
     * Add product pages to sitemap
     * 
     * @param Sitemap $sitemap
     */
    private function addProductPages(Sitemap $sitemap): void
    {
        Product::where('active', 1)
            ->whereNotNull('featured_image')
            ->chunk(100, function ($products) use ($sitemap) {
                foreach ($products as $product) {
                    // Only decode the permalink here for products
                    $decodedPermalink = urldecode($product->permalink);
                    
                    $sitemap->add(
                        Url::create(self::FRONTEND_URL . "/product/{$decodedPermalink}")
                            ->setLastModificationDate($product->updated_at ?? now())
                            ->setChangeFrequency('weekly')
                            ->setPriority(0.6)
                    );
                }
            });
    }

    /**
     * Add category pages to sitemap with pagination
     * 
     * @param Sitemap $sitemap
     */
    private function addCategoryPages(Sitemap $sitemap): void
    {
        Category::with(['activeProducts' => function ($query) {
            $query->where('active', 1);
        }])
        ->get()
        ->each(function ($category) use ($sitemap) {
            // Main category page - use proper URL construction
            $categoryUrl = self::FRONTEND_URL . "/shop?category={$category->sku}";
            $sitemap->add(
                Url::create($categoryUrl)
                    ->setLastModificationDate($category->updated_at ?? now())
                    ->setChangeFrequency('weekly')
                    ->setPriority(0.7)
            );

            // Pagination
            $totalProducts = $category->activeProducts->count();
            $totalPages = max(1, ceil($totalProducts / self::PRODUCTS_PER_PAGE));

            for ($page = 2; $page <= $totalPages; $page++) {
                $paginatedUrl = self::FRONTEND_URL . "/shop?category={$category->sku}&page={$page}";
                $sitemap->add(
                    Url::create($paginatedUrl)
                        ->setLastModificationDate($category->updated_at ?? now())
                        ->setChangeFrequency('weekly')
                        ->setPriority(0.5)
                );
            }
        });
    }

    /**
     * Add subcategory pages to sitemap with pagination
     * 
     * @param Sitemap $sitemap
     */
    private function addSubcategoryPages(Sitemap $sitemap): void
    {
        ChildCategory::with(['activeProducts'])
            ->get()
            ->each(function ($subcategory) use ($sitemap) {
                // Main subcategory page
                $subcategoryUrl = self::FRONTEND_URL . "/shop?child_category={$subcategory->child_sku}";
                $sitemap->add(
                    Url::create($subcategoryUrl)
                        ->setLastModificationDate($subcategory->updated_at ?? now())
                        ->setChangeFrequency('weekly')
                        ->setPriority(0.7)
                );

                // Pagination
                $totalProducts = $subcategory->activeProducts->count();
                $totalPages = max(1, ceil($totalProducts / self::PRODUCTS_PER_PAGE));

                for ($page = 2; $page <= $totalPages; $page++) {
                    $paginatedUrl = self::FRONTEND_URL . "/shop?child_category={$subcategory->child_sku}&page={$page}";
                    $sitemap->add(
                        Url::create($paginatedUrl)
                            ->setLastModificationDate($subcategory->updated_at ?? now())
                            ->setChangeFrequency('weekly')
                            ->setPriority(0.5)
                    );
                }
            });
    }

    private function addBrandPages(Sitemap $sitemap): void
    {
        Brand::with(['activeProducts'])
            ->where('active', 1)
            ->get()
            ->each(function ($brand) use ($sitemap) {
                // Main brand page
                $brandUrl = self::FRONTEND_URL . "/brand?brand={$brand->permalink}";
                $sitemap->add(
                    Url::create($brandUrl)
                        ->setLastModificationDate($brand->updated_at ?? now())
                        ->setChangeFrequency('weekly')
                        ->setPriority(0.7)
                );

                // Pagination
                $totalProducts = $brand->activeProducts->count();
                $totalPages = max(1, ceil($totalProducts / self::PRODUCTS_PER_PAGE));

                for ($page = 2; $page <= $totalPages; $page++) {
                    $paginatedUrl = self::FRONTEND_URL . "/brand?brand={$brand->permalink}&page={$page}";
                    $sitemap->add(
                        Url::create($paginatedUrl)
                            ->setLastModificationDate($brand->updated_at ?? now())
                            ->setChangeFrequency('weekly')
                            ->setPriority(0.5)
                    );
                }
            });
    }

    /**
     * Save and optimize sitemap XML
     * 
     * @param Sitemap $sitemap
     */
    private function saveSitemap(Sitemap $sitemap): void
    {
        $sitemap->writeToFile(public_path('sitemap.xml'));
        
        // Additional XML optimization
        $sitemapPath = public_path('sitemap.xml');
        $sitemapContent = file_get_contents($sitemapPath);
        
        // Remove any double-encoded URLs or unnecessary prefixes
        $optimizedContent = preg_replace_callback(
            '/<loc>(.*?)<\/loc>/i', 
            function($matches) {
                // Only decode the permalink and do no further URL normalization for product URLs
                $cleanUrl = urldecode($matches[1]);
                return "<loc>" . htmlspecialchars($cleanUrl) . "</loc>";
            }, 
            $sitemapContent
        );

        file_put_contents($sitemapPath, $optimizedContent);
    }
}
