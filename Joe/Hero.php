<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Hero extends Model
{
    public function allHeroes(){
		return DB::select('select * from heroes;');
    }

    public function getHero($id){
    	return DB::select('select * from heroes where id='.$id.';');
    }
}
