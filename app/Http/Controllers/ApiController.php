<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;


class ApiController extends BaseController
{

    public function create(Request $request){
        function generateRandomString($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
                return $randomString;
	}

	$deviceinfo['model'] = $request->input('model');
	$deviceinfo['brand'] = $request->input('brand');
 	$deviceinfo['device'] = $request->input('device');
	$deviceinfo['hash'] = $request->input('hash');

	if($deviceinfo['hash'] == sha1($deviceinfo['brand'].".".$deviceinfo['model'].".".$deviceinfo['device'])){
    	    $existsflag = 1;
            $rand32 = "";
	    $rand64 = generateRandomString(64);
        
	    while($existsflag == 1){
	        $rand32 = generateRandomString(32);
	        $result = DB::table('users')->where('deviceuid', $rand32)->exists();
	        if($result == 0){
	            $result = DB::table('users')->insert(['deviceuid' => $rand32, 'hashkey' => $rand64]);
		    $existsflag = 0;
	        }
            }
	
	    return response()->json([
	        'deviceuid' => $rand32,
	        'hk' => $rand64
 	    ]);
	}
	else{
	    abort(403, 'Unauthorized action.');
	}

    }

    public function playad(Request $request){
    	$d['deviceuid'] = $request->input['deviceuid'];
	$d['model']	= $request->input['model'];
	$d['brand']	= $request->input['brand'];
	$d['device']	= $request->input['device'];
	$d['buildid']	= $request->input['buildid'];
	$d['manufacturer'] = $request->input['manufacturer'];
	$d['user']	   = $request->input['user'];
	$d['product']	   = $request->input['product'];
	$d['releaseversion'] = $request->input['releaseversion'];
	$d['sdkversion']     = $request->input['sdkversion'];

    	$user = DB::table('users')->select(['id','hashkey'])->where('deviceuid', $d['deviceuid'])->first();

	if(!empty($user) && (sha1($d['deviceuid'].".".$user->hashkey.".".$d['model'])) == $d['hash']){
	    $result = DB::table('transactions')->insertGetId(['deviceuid' => $d['deviceuid'], 'userid' => $user->id,
	    	      	'playad' => "now()", 'model' => $d['model'], 'brand' => $d['brand'], 'device' => $d['device'],
			'buildid' => $d['buildid'], 'manufacturer' => $d['manufacturer'], 'user' => $d['user'], 'product' => $d['product'],
			'releaseversion' => $d['releaseversion'], 'sdkversion' => $d['sdkversion']], 'id');
	    return response()->json([
                'transactionid' => $result
            ]);
	}
	else{
	/*
	    $result = DB::table('transactions')->insertGetId(['deviceuid' => $request->input('deviceuid'), 'userid' => "123",
                        'playad' => "now()"], 'id');
	    print_r($result);
	    echo "</br>" . $result;
	    */

	    $returnhash = sha1($d['deviceuid'].".".$user->hashkey.".".$d['model']);
	    return response()->json([
                'transactinid' => $returnhash
            ]);
	    //abort(403, 'Unauthorized action.');
	    
	    
	}


	/*
	$hashkey = $request->input('hk');
        $model = $request->input('model');
	$brand = $request->input('brand');
	$device = $request->input('device');
	$buildid = $request->input('buildid');
	$manufacturer = $request->input('manufacturer');
	$user = $request->input('user');
	$product = $request->input('product');
	$releaseversion = $request->input('releaseversion');
	$sdkverstion = $request->input('sdkversion');

	

	$result = DB::table('users')->insert(['deviceuid' => $rand32], ['hashkey' => $rand64]);
	*/
    }

    public function testcontroller($id)
    {

	$result = DB::table('users')->where('deviceuid', 'test')->exists();
	print_r($result);


	/*
        echo 'id is:' . $id;
        echo '<br>';

	$data['id'] = $id;

	//$users = DB::select ('select * from users');
	//$users = DB::connection('pgsql')->select('select * from users');
	$result = DB::connection('pgsql')->select(DB::raw("select * from users"));

	print_r($result);
	
        return view('test', $data);
	*/
    }
}
