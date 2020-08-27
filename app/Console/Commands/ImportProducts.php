<?php

namespace App\Console\Commands;

use App\Http\Controllers\Exports\DealersImp;
use App\Http\Controllers\Exports\EleImp;
use App\Http\Controllers\Exports\ProductsImp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Products';

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
	    $this->alert('Importing Products From Excel');
	    $file = $this->ask('Enter excel file name in public directory?');
	    while(!file_exists(public_path($file))){
		    $this->comment('File not found in public directory, retry or use CTRL+C to exit');
		    $file = $this->ask('Enter excel file name in public directory?');
	    }
	    Excel::import(new ProductsImp,public_path($file));

    }
}
