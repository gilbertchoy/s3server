<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \Datetime;

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

	$d['deviceuid'] = $request->input('deviceuid');
        $d['model']     = $request->input('model');
        $d['brand']     = $request->input('brand');
        $d['device']    = $request->input('device');
        $d['buildid']   = $request->input('buildid');
        $d['manufacturer'] = $request->input('manufacturer');
        $d['user']         = $request->input('user');
        $d['product']      = $request->input('product');
        $d['releaseversion'] = $request->input('releaseversion');
        $d['sdkversion']     = $request->input('sdkversion');
        $d['hash'] = $request->input('hash');

	//echo "hash is:".sha1($d['brand'].".".$d['model'].".".$d['device']);


	if($d['hash'] == sha1($d['brand'].".".$d['model'].".".$d['device'])){
    	    $existsflag = 1;
            $rand32 = "";
	    $rand64 = generateRandomString(64);
        
	    while($existsflag == 1){
	        $rand32 = generateRandomString(32);
	        $result = DB::table('users')->where('deviceuid', $rand32)->exists();
	        if($result == 0){
	            //$result = DB::table('users1')->insert(['deviceuid' => $rand32, 'hashkey' => $rand64]);
		    $result = DB::table('users1')->insert(['deviceuid' => $rand32, 'hashkey' => $rand64,
                        'model' => $d['model'], 'brand' => $d['brand'], 'device' => $d['device'],
                        'buildid' => $d['buildid'], 'manufacturer' => $d['manufacturer'], 'user' => $d['user'], 'product' => $d['product'],
                        'releaseversion' => $d['releaseversion'], 'sdkversion' => $d['sdkversion']]);
		    
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
    	$d['deviceuid'] = $request->input('deviceuid');
	$d['model']	= $request->input('model');
	$d['brand']	= $request->input('brand');
	$d['device']	= $request->input('device');
	$d['buildid']	= $request->input('buildid');
	$d['manufacturer'] = $request->input('manufacturer');
	$d['user']	   = $request->input('user');
	$d['product']	   = $request->input('product');
	$d['releaseversion'] = $request->input('releaseversion');
	$d['sdkversion']     = $request->input('sdkversion');
	$d['hash'] = $request->input('hash');

    	$user = DB::table('users1')->where('deviceuid', $d['deviceuid'])->first();
	$hash = sha1($d['deviceuid'].".".$user->hashkey.".".$d['model']);
	
	if(!empty($user) && $hash == $d['hash']){
	    $result = DB::table('transactions')->insertGetId(['deviceuid' => $d['deviceuid'], 'userid' => $user->id,
	    	      	'playad' => "now()", 'model' => $d['model'], 'brand' => $d['brand'], 'device' => $d['device'],
			'buildid' => $d['buildid'], 'manufacturer' => $d['manufacturer'], 'user' => $d['user'], 'product' => $d['product'],
			'releaseversion' => $d['releaseversion'], 'sdkversion' => $d['sdkversion']], 'id');
	    return response()->json([
                'transactionid' => $result
            ]);
	}
	else{
	    abort(403, 'Unauthorized action.');
	}
	//bert add later: if userid doesnt exist, creaete playad entry anyways
    }

    public function adclosed(Request $request){

    //working on seems to work local, finish testing

        $d['deviceuid'] = $request->input('deviceuid');
        $d['model']     = $request->input('model');
        $d['brand']     = $request->input('brand');
        $d['device']    = $request->input('device');
        $d['buildid']   = $request->input('buildid');
        $d['manufacturer'] = $request->input('manufacturer');
        $d['user']         = $request->input('user');
        $d['product']      = $request->input('product');
        $d['releaseversion'] = $request->input('releaseversion');
        $d['sdkversion']     = $request->input('sdkversion');
        $d['hash'] = $request->input('hash');
	$d['transactionid'] = $request->input('transactionid');
  
	$user = DB::table('users1')->where('deviceuid', $d['deviceuid'])->first();

	function createNewTransaction($d,$user,$test){
	    DB::table('transactions')->insert(['deviceuid' => $d['deviceuid'], 'userid' => $user->id,
                        'adclosed' => "now()", 'model' => $test, 'brand' => $d['brand'], 'device' => $d['device'],
                        'buildid' => $d['buildid'], 'manufacturer' => $d['manufacturer'], 'user' => $d['user'], 'product' => $d['product'],
                        'releaseversion' => $d['releaseversion'], 'sdkversion' => $d['sdkversion']]);
	}

	//print_r($d);
	//echo "</br></br>";
	//print_r($user);
	$hash = sha1($d['transactionid'].".".$d['deviceuid'].".".$user->hashkey.".".$d['model']);
	//echo "</br></br>";
	//echo "hash is:" . $hash;
	
        if($hash == $d['hash']){
	    $transaction = DB::table('transactions')->select('id','playad', 'adclosed')->where('deviceuid', $d['deviceuid'])->first();
	    if(!empty($transaction)){
	        if(DateTime::createFromFormat('Y-m-d H:i:s.u', $transaction->playad) !== FALSE){
		    createNewTransaction($d,$user,"test");
		}
		else{
		    DB::table('transactions')->where('id', $d['transactionid'])->update(['adclosed' => 'now()']);
		    //DB::table('transactions')->set('adclosed', "now()")->where('id', $transaction->id);
		}
            }
	    else{
		createNewTransaction($d,$user,"test1");
	    }
	    return response()->json([
                'transactionid' => "200"
            ]);
	}
        else{
            abort(403, 'Unauthorized action.');
        }
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
