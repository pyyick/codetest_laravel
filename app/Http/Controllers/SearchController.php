<?php

namespace App\Http\Controllers;

use App\FirstName;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $keyword = ucfirst(mb_strtolower($request->label));
        $firstName = FirstName::where("label", 'like', "{$keyword}%")->limit(20)->get();
        $firstName = $firstName->map(function($name, $key){
            switch ($name["type"]){
                case 0:
                    $name["type"] = "ambiguous";
                    break;
                case 1:
                    $name["type"] = "masculine";
                    break;
                case 2:
                    $name["type"] = "feminine";
                    break;
            }
            switch ($name["gender"]){
                case 1:
                    $name["gender"] = "male";
                    break;
                case 2:
                    $name["gender"] = "female";
                    break;
            }
            return $name;
        });
        return response()->json($firstName);
    }
}
