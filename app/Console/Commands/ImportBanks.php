<?php
/*
	php artisan banks:import
	/assets/sl_bank_code.json
*/
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class ImportBanks extends Command
{
    /**
     * The name and signature of the console command.
     * TO
     *
     * @var string
     */
    protected $signature = 'banks:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Bank Info';

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
	    $this->alert('Importing Banks from JSON');
	    $file = $this->ask('Enter json file name in public directory?');
	    while(!file_exists(public_path($file))){
	    	$this->comment('File not found in public directory, retry or use CTRL+C to exit');
		    $file = $this->ask('Enter json file name in public directory?');
	    }
	    $content = json_decode(File::get(public_path($file)));
	    foreach ($content as $key=>$val){
	    	foreach ($val as $code=>$name){
			    DB::connection( 'mongodb' )->collection( 'banks' )->insert(
			    array(
				    '_id' => $code,
				    'bankname' => $name,
			    )
		    );
		    }
	    }
    }
}
