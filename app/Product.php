<?php

namespace App;



use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{

	protected $fillable = ['code','supplier_id','name','brand','retail_price','dealer_price','distributor_price','stock'];

	protected $guarded = [];

	public static function boot() {
	    parent::boot();

	    //while creating/inserting item into db  
	    static::creating(function ($item) {
	    	if(!$item->code )
	        $item->code = str_pad(Product::count() + 1,10,0,STR_PAD_LEFT); //assigning value
	    });
	}


	public function purchaseOrderItems(){
		return $this->hasMany('App\PurchaseOrderItems');
	}

	/**
	 * @return $this
	 * Get discount for the product
	 */
	public function productDiscounts(){
		$today = Carbon::parse();
		return $this->hasMany('App\ProductDiscount')
			->where('start_at','<',$today )
			->where('end_at','>',$today )
			->where('status','active' )
			->orderBy('eligible_qty');
	}

	public function supplier(){
		return $this->belongsTo('App\Supplier');
	}

	public function getCreatedAtAttribute($value){
		return date('Y-m-d', strtotime($value));
	}

	public function getUpdatedAtAttribute($value){
		return date('Y-m-d', strtotime($value));
	}


	public static function getImageList($id, $nameonly = true ):array{
		if(is_dir(public_path('product/'.$id))){
			$files = [];
			foreach(File::files(public_path('product/'.$id)) as $path) {
				$file = pathinfo($path);
				$url = "product/$id/{$file['filename']}.{$file['extension']}";
				if($nameonly){
					array_push($files, $url);
				}else{
					$file['url'] = $url;
					$file['size'] = filesize($path);
					array_push($files, $file);
				}
			}
			return $files;
		}
		return [];
	}

}
