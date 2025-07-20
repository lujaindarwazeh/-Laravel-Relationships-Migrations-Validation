<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class TranslateWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:words {words*} {--lang=en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate an array of words into the specified language';

    /**
     * Execute the console command.
     *
     * @return int
     */
     public function handle()
    {
       
        $lang = $this->option('lang');
       

        config(['app.locale' => $lang]);

       
        $words = $this->argument('words');

        $translated = [];

        foreach ($words as $word) {
            $translated[] = __("messages.$word");
        }




       echo"Original: " . implode(' ', $words)."\n";
        echo"Translated ($lang): " . implode(' ', $translated)."\n";
        $url = config('app.lujain_url');
        echo "Lujain URL is: $url\n";

    }
}
