<?php

namespace App\Console\Commands;

use App\Http\Controllers\Exports\EleImp;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportElectrician extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'electrician:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Electrician';

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
	    $this->alert('Importing Electrician From Excel');
	    $file = $this->ask('Enter excel file name in public directory?');
	    while(!file_exists(public_path($file))){
	    	$this->comment('File not found in public directory, retry or use CTRL+C to exit');
		    $file = $this->ask('Enter excel file name in public directory?');
	    }
	    Excel::import(new EleImp,public_path($file));
    }
}
