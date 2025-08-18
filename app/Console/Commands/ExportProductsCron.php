<?php

namespace App\Console\Commands;

use App\Services\ProductExportService;
use Illuminate\Console\Command;

class ExportProductsCron extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export products to CSV files for public download';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ProductExportService $exportService)
    {
        // Start exporting the default product list
        $this->info('Starting default product export...');
        $resultDefault = $exportService->exportProducts();

        if ($resultDefault) {
            $this->info('Default product export completed successfully!');
            $this->info('File available at: ' . $exportService->getFileUrl());
        } else {
            $this->error('Default product export failed. Check logs for details.');
            return Command::FAILURE;
        }

        // Start exporting the alternate product list
        $this->info('Starting alternate product export...');
        $resultAlternate = $exportService->exportAlternateProducts();

        if ($resultAlternate) {
            $this->info('Alternate product export completed successfully!');
            $this->info('File available at: ' . $exportService->getAlternateFileUrl());
            return Command::SUCCESS;
        } else {
            $this->error('Alternate product export failed. Check logs for details.');
            return Command::FAILURE;
        }
    }
}
