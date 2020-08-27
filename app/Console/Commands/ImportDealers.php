<?php

namespace App\Console\Commands;

use App\Http\Controllers\Exports\DealersImp;
use App\Http\Controllers\Exports\EleImp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ImportDealers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dealers:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Dealers';

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
     * @return mixed
     */
    public function handle()
    {
        //
	    $this->alert('Importing Dealers From Excel');
	    $folder = $this->ask('Enter excel folder name in public directory?');

	    while(!is_dir(public_path($folder))){
	    	$this->comment('Folder not found in public directory, retry or use CTRL+C to exit');
		    $folder = $this->ask('Enter folder name in public directory with dealers list?');
	    }
	    $files = scandir(public_path($folder));
	    foreach ($files as $file){
		    if (preg_match('/.xlsx/', $file))
		    {
			    echo "Importing $file\n";
			    Excel::import(new DealersImp(),public_path($folder.'/'.$file));
		    }

	    }

    }
}
