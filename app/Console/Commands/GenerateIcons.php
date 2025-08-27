<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'icons:generate {--source=logo.png : Source logo file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all app icons from the main logo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sourceFile = $this->option('source');
        $sourcePath = public_path('img/' . $sourceFile);
        
        if (!file_exists($sourcePath)) {
            $this->error("Source file not found: {$sourcePath}");
            return 1;
        }

        $this->info("Generating icons from: {$sourceFile}");

        // Android icons
        $androidIcons = [
            'android-icon-36x36.png' => 36,
            'android-icon-48x48.png' => 48,
            'android-icon-72x72.png' => 72,
            'android-icon-96x96.png' => 96,
            'android-icon-144x144.png' => 144,
            'android-icon-192x192.png' => 192,
        ];

        // Apple icons
        $appleIcons = [
            'apple-icon-57x57.png' => 57,
            'apple-icon-60x60.png' => 60,
            'apple-icon-72x72.png' => 72,
            'apple-icon-76x76.png' => 76,
            'apple-icon-114x114.png' => 114,
            'apple-icon-120x120.png' => 120,
            'apple-icon-144x144.png' => 144,
            'apple-icon-152x152.png' => 152,
            'apple-icon-180x180.png' => 180,
            'apple-icon-precomposed.png' => 180,
            'apple-icon.png' => 180,
        ];

        // Favicon icons
        $faviconIcons = [
            'favicon-16x16.png' => 16,
            'favicon-32x32.png' => 32,
            'favicon-96x96.png' => 96,
        ];

        // Microsoft icons
        $msIcons = [
            'ms-icon-70x70.png' => 70,
            'ms-icon-144x144.png' => 144,
            'ms-icon-150x150.png' => 150,
            'ms-icon-310x310.png' => 310,
        ];

        $allIcons = array_merge($androidIcons, $appleIcons, $faviconIcons, $msIcons);

        $bar = $this->output->createProgressBar(count($allIcons));
        $bar->start();

        foreach ($allIcons as $filename => $size) {
            $outputPath = public_path('img/' . $filename);
            
            try {
                $this->resizeImage($sourcePath, $outputPath, $size, $size);
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nError generating {$filename}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->line('');
        $this->info('All icons generated successfully!');

        // Generate favicon.ico
        $this->generateFaviconIco($sourcePath);

        return 0;
    }

    /**
     * Resize image using GD
     */
    private function resizeImage($sourcePath, $outputPath, $width, $height)
    {
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            throw new \Exception("Invalid image file");
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $imageType = $imageInfo[2];

        // Create source image resource
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new \Exception("Unsupported image type");
        }

        // Create destination image
        $destImage = imagecreatetruecolor($width, $height);

        // Preserve transparency for PNG
        if ($imageType == IMAGETYPE_PNG) {
            imagealphablending($destImage, false);
            imagesavealpha($destImage, true);
            $transparent = imagecolorallocatealpha($destImage, 255, 255, 255, 127);
            imagefilledrectangle($destImage, 0, 0, $width, $height, $transparent);
        }

        // Resize image
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

        // Save image
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($destImage, $outputPath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($destImage, $outputPath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($destImage, $outputPath);
                break;
        }

        // Clean up
        imagedestroy($sourceImage);
        imagedestroy($destImage);
    }

    /**
     * Generate favicon.ico file
     */
    private function generateFaviconIco($sourcePath)
    {
        $this->info('Generating favicon.ico...');
        
        try {
            $this->resizeImage($sourcePath, public_path('img/favicon.ico'), 32, 32);
            $this->info('Favicon.ico generated!');
        } catch (\Exception $e) {
            $this->error('Error generating favicon.ico: ' . $e->getMessage());
        }
    }
}
