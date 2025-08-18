<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapService;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap';

    public function handle(SitemapService $sitemapService)
    {
        $this->info('Generating sitemap...');
        $sitemapService->generate();
        $this->info('Sitemap generated successfully!');
    }
}