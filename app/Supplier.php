<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
   protected $fillable = [ 'name', 'telephone', 'block', 'street', 'city'];

   public function purchaseOrders($status = null){
   	if($status)
	   return $this->hasMany( 'App\PurchaseOrder', 'supplier_id' )
		   ->where('status',$status);
   	else
	    return $this->hasMany( 'App\PurchaseOrder', 'supplier_id' );
   }

}
