<?php
/**
 * Icon Analysis Script for Sydney Quality Meats
 * Analyzes the large icons.min.css file to identify optimization opportunities
 */

class IconAnalyzer {
    private $iconFile = 'public/assets/css/icons.min.css';
    
    public function analyze() {
        if (!file_exists($this->iconFile)) {
            echo "Error: Icon file not found: {$this->iconFile}\n";
            return;
        }
        
        $content = file_get_contents($this->iconFile);
        $fileSize = strlen($content);
        
        echo "=== Icon File Analysis ===\n";
        echo "File: {$this->iconFile}\n";
        echo "Size: " . number_format($fileSize) . " bytes (" . round($fileSize / 1024 / 1024, 2) . " MB)\n\n";
        
        // Analyze icon classes
        $this->analyzeIconClasses($content);
        
        // Check for unused icons
        $this->findUnusedIcons($content);
        
        // Suggest optimizations
        $this->suggestOptimizations($fileSize);
    }
    
    private function analyzeIconClasses($content) {
        // Find all icon class definitions
        preg_match_all('/\.([a-zA-Z0-9_-]+):before/', $content, $matches);
        $iconClasses = array_unique($matches[1]);
        
        echo "=== Icon Classes Found ===\n";
        echo "Total unique icon classes: " . count($iconClasses) . "\n";
        
        // Sample of icon classes
        $sampleIcons = array_slice($iconClasses, 0, 20);
        echo "Sample icon classes:\n";
        foreach ($sampleIcons as $icon) {
            echo "  - {$icon}\n";
        }
        echo "... and " . (count($iconClasses) - 20) . " more\n\n";
    }
    
    private function findUnusedIcons($content) {
        echo "=== Usage Analysis ===\n";
        
        // Search for icon usage in blade files
        $bladeFiles = $this->findBladeFiles();
        $usedIcons = [];
        
        foreach ($bladeFiles as $file) {
            $fileContent = file_get_contents($file);
            preg_match_all('/class="[^"]*([a-zA-Z0-9_-]+)[^"]*"/', $fileContent, $matches);
            
            foreach ($matches[1] as $class) {
                if (strpos($content, ".{$class}:before") !== false) {
                    $usedIcons[] = $class;
                }
            }
        }
        
        $usedIcons = array_unique($usedIcons);
        echo "Icons found in templates: " . count($usedIcons) . "\n";
        
        if (count($usedIcons) > 0) {
            echo "Sample used icons:\n";
            $sampleUsed = array_slice($usedIcons, 0, 10);
            foreach ($sampleUsed as $icon) {
                echo "  - {$icon}\n";
            }
        }
        
        echo "\n";
    }
    
    private function findBladeFiles() {
        $bladeFiles = [];
        $directories = ['resources/views/Admin', 'resources/views/Website'];
        
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
                foreach ($iterator as $file) {
                    if ($file->isFile() && $file->getExtension() === 'php') {
                        $bladeFiles[] = $file->getPathname();
                    }
                }
            }
        }
        
        return $bladeFiles;
    }
    
    private function suggestOptimizations($fileSize) {
        echo "=== Optimization Suggestions ===\n";
        
        $suggestions = [
            "1. Replace with Font Awesome CDN (saves ~4MB)",
            "2. Use Bootstrap Icons (smaller, modern)",
            "3. Convert to SVG icons (better performance)",
            "4. Load icons on-demand (lazy loading)",
            "5. Use icon subsets (only load needed icons)"
        ];
        
        foreach ($suggestions as $suggestion) {
            echo "  {$suggestion}\n";
        }
        
        echo "\n=== Quick Wins ===\n";
        echo "1. Replace icons.min.css with CDN version:\n";
        echo "   <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">\n\n";
        
        echo "2. Or use Bootstrap Icons (much smaller):\n";
        echo "   <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css\">\n\n";
        
        echo "3. Estimated savings: " . round($fileSize / 1024 / 1024, 2) . " MB\n";
    }
}

// Run the analysis
$analyzer = new IconAnalyzer();
$analyzer->analyze();
?> 