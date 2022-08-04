<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\csvParser;

class importcsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:csvImport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse the csv files in public\csv directory and save to database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        CsvParser::parseCsv();
    }
}
