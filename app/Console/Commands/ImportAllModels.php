<?php

namespace App\Console\Commands;

use App\Spork;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ImportAllModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spork:scout-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        array_map(function ($featureArray) {
            try { 
                return array_map(function ($file) use ($featureArray) {
                    // include sprintf(base_path('system/%s/src/Models'), strtolower($featureArray['name'])) . '/' . $file;
                    $modelToImport = sprintf('Spork\\%s\\Models\\%s', $featureArray['name'], str_replace('.php', '', $file));

                    if (in_array(Searchable::class, class_uses($modelToImport))) {
                        \Artisan::call('scout:import', [
                            'model' => $modelToImport,
                        ]);
                    }
                }, array_filter(scandir(sprintf(base_path('system/%s/src/Models'), strtolower($featureArray['name']))), fn ($file) => $file !== '.' && $file !== '..'));
            } catch (\Throwable $e) {
                if (stripos($e->getMessage(), 'No such file or directory') !== false) {
                    return;
                }
                dd($e);

                return null;
            }
        }, Spork::$features);
        return 0;
    }
}
