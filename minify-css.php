<?php
/**
 * CSS Minification Script for Sydney Quality Meats
 * This script combines and minifies CSS files to reduce payload size
 */

class CSSMinifier {
    private $cssFiles = [
        'public/assets/libs/jsvectormap/css/jsvectormap.min.css',
        'public/assets/libs/swiper/swiper-bundle.min.css',
        'public/assets/css/bootstrap.min.css',
        'public/assets/css/icons.min.css',
        'public/assets/css/app.min.css',
        'public/assets/css/newstyle.min.css',
        'public/assets/css/custom.min.css'
    ];
    
    private $outputFile = 'public/assets/css/combined.min.css';
    
    public function combineAndMinify() {
        $combinedCSS = '';
        $totalSize = 0;
        
        echo "Starting CSS combination and minification...\n";
        
        foreach ($this->cssFiles as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);
                $size = strlen($content);
                $totalSize += $size;
                
                echo "Processing: {$file} ({$size} bytes)\n";
                $combinedCSS .= "/* {$file} */\n" . $content . "\n";
            } else {
                echo "Warning: File not found: {$file}\n";
            }
        }
        
        // Minify the combined CSS
        $minifiedCSS = $this->minifyCSS($combinedCSS);
        $minifiedSize = strlen($minifiedCSS);
        
        // Save the combined and minified CSS
        file_put_contents($this->outputFile, $minifiedCSS);
        
        echo "\n=== Results ===\n";
        echo "Original total size: " . number_format($totalSize) . " bytes\n";
        echo "Minified size: " . number_format($minifiedSize) . " bytes\n";
        echo "Savings: " . number_format($totalSize - $minifiedSize) . " bytes (" . round((($totalSize - $minifiedSize) / $totalSize) * 100, 2) . "%)\n";
        echo "Output file: {$this->outputFile}\n";
        
        return $minifiedSize;
    }
    
    private function minifyCSS($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Remove whitespace around certain characters
        $css = preg_replace('/\s*([{}:;,])\s*/', '$1', $css);
        
        // Remove trailing semicolons
        $css = str_replace(';}', '}', $css);
        
        // Remove leading/trailing whitespace
        $css = trim($css);
        
        return $css;
    }
    
    public function createOptimizedVersion() {
        // Create a more optimized version that excludes the large icons file
        $optimizedFiles = [
            'public/assets/libs/jsvectormap/css/jsvectormap.min.css',
            'public/assets/libs/swiper/swiper-bundle.min.css',
            'public/assets/css/bootstrap.min.css',
            'public/assets/css/app.min.css',
            'public/assets/css/newstyle.min.css',
            'public/assets/css/custom.min.css'
        ];
        
        $combinedCSS = '';
        $totalSize = 0;
        
        echo "\nCreating optimized version (without large icons file)...\n";
        
        foreach ($optimizedFiles as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);
                $size = strlen($content);
                $totalSize += $size;
                
                echo "Processing: {$file} ({$size} bytes)\n";
                $combinedCSS .= "/* {$file} */\n" . $content . "\n";
            }
        }
        
        $minifiedCSS = $this->minifyCSS($combinedCSS);
        $minifiedSize = strlen($minifiedCSS);
        
        $optimizedOutputFile = 'public/assets/css/optimized.min.css';
        file_put_contents($optimizedOutputFile, $minifiedCSS);
        
        echo "\n=== Optimized Results ===\n";
        echo "Original total size: " . number_format($totalSize) . " bytes\n";
        echo "Minified size: " . number_format($minifiedSize) . " bytes\n";
        echo "Savings: " . number_format($totalSize - $minifiedSize) . " bytes (" . round((($totalSize - $minifiedSize) / $totalSize) * 100, 2) . "%)\n";
        echo "Output file: {$optimizedOutputFile}\n";
        
        return $minifiedSize;
    }
}

// Run the minifier
$minifier = new CSSMinifier();
$minifier->combineAndMinify();
$minifier->createOptimizedVersion();

echo "\nTo use the optimized CSS, update your links.blade.php file to include:\n";
echo "<!-- <link href=\"{{ asset('assets/css/optimized.min.css') }}\" rel=\"stylesheet\" type=\"text/css\"> -->\n";
echo "<!-- <link href=\"{{ asset('assets/css/icons.min.css') }}\" rel=\"stylesheet\" type=\"text/css\"> -->\n";
?> 