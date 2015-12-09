<?php 
namespace App\Http\Controllers;

use App\Hero;
use App\Http\Controllers\Controller;
use Response;


class HeroController extends Controller{

	protected $hero = null;

	public function __construct(Hero $hero){
		$this->hero  = $hero;
	}

	public function allHeroes(){
		//return $this->hero->allHeroes();
		return "all heroes";
	}

	public function getHero($id){
		/*$heroRes = $this->hero->getHero($id);
		if(empty($heroRes)){
			return Response::json(['success' => false]);
		}else{
			return Response::json(['success' => true, 'hero' => $heroRes[0]]);
		}*/

		return "hero ".$id;
	}

	public function saveHero(){
		//return $this->hero->saveHero();		
		return "save Hero";
	}

	public function updateHero($id){
		//return $this->hero->updateHero($id);	

		return "update Hero ".$id;
	}

	public function deleteHero($id){
		//return $this->hero->deleteHero($id);	
		return "delete Hero ".$id;
	}

}