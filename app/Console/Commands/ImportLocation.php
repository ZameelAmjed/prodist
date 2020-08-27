<?php
/*
	php artisan location:import
	--updateregion {"Embilipitiya": "Southern",}
	--updatecode  { "Gampola": "NEY",}
*/
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class ImportLocation extends Command
{
    /**
     * The name and signature of the console command.
     * TO
     *
     * @var string
     */
    protected $signature = 'location:import {--updatecode} {--updateregion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Area->Region->Region Code Info';

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
        if($this->options('updatecode')){
	        $this->alert('Updating Region Codes from JSON');
	        $file = $this->ask('Enter json file name in public directory?');
	        while(!file_exists(public_path($file))){
		        $this->comment('File not found in public directory, retry or use CTRL+C to exit');
		        $file = $this->ask('Enter json file name in public directory?');
	        }

	        $content = json_decode(File::get(public_path($file)));
	        foreach ($content as $key=>$val){
		        DB::connection( 'mongodb' )->collection( 'area' )
		          ->where("_id",$key)->update(['code'=>$val]);
	        }
        }elseif($this->options('updateregion')){
	        $this->alert('Updating Region for cities from JSON');
	        $file = $this->ask('Enter json file name in public directory?');
	        while(!file_exists(public_path($file))){
		        $this->comment('File not found in public directory, retry or use CTRL+C to exit');
		        $file = $this->ask('Enter json file name in public directory?');
	        }

	        $content = json_decode(File::get(public_path($file)));
	        foreach ($content as $key=>$val){
		        DB::connection( 'mongodb' )->collection( 'area' )
		          ->where("_id",$key)->update(['region'=>$val]);
	        }
        }else{
	        $this->alert('Importing Region and Region Codes from JSON');
	        $file = $this->ask('Enter json file name in public directory?');
	        while(!file_exists(public_path($file))){
		        $this->comment('File not found in public directory, retry or use CTRL+C to exit');
		        $file = $this->ask('Enter json file name in public directory?');
	        }
	        $content = json_decode(File::get(public_path($file)));
	        foreach ($content as $key=>$val){
		        foreach ($val->options as $val2){
			        DB::connection( 'mongodb' )->collection( 'area' )->insert(
				        array(
					        '_id' => $val2,
					        'region' => $key,
					        'province' => $key,
					        'code' => strtoupper(substr($val2,0,3)),
				        )
			        );
		        }

	        }
        }

    }
}
