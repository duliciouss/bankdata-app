<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GeminiService; // Pastikan Anda mengimpor GeminiService
use Exception;

class CheckGeminiModelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gemini:check-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks available Gemini models using the configured API key.';

    /**
     * Execute the console command.
     */
    public function handle(GeminiService $geminiService)
    {
        $this->info('Checking available Gemini models...');

        try {
            $models = $geminiService->listAvailableModels();

            if (empty($models['models'])) {
                $this->warn('No models found or an empty response was received.');
                return Command::FAILURE;
            }

            $this->info('--- Available Gemini Models ---');
            foreach ($models['models'] as $model) {
                $this->line(sprintf(
                    "Name: %s | Version: %s | Input: %s | Output: %s | Supported Methods: %s",
                    $model['name'] ?? 'N/A',
                    $model['version'] ?? 'N/A',
                    $model['inputTokenLimit'] ?? 'N/A',
                    $model['outputTokenLimit'] ?? 'N/A',
                    implode(', ', $model['supportedGenerationMethods'] ?? ['N/A'])
                ));
            }
            $this->info('-------------------------------');
            $this->info('Check the "Supported Methods" column to ensure "generateContent" is listed for your desired model (e.g., models/gemini-pro).');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error('Failed to list Gemini models: ' . $e->getMessage());
            $this->error('Please ensure your GEMINI_API_KEY in .env is correct and the Generative Language API is enabled for your project.');
            return Command::FAILURE;
        }
    }
}
