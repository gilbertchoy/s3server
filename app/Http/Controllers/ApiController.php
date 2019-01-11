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

    public function playad(Request $request)
    {
	$data['model'] = 'this is model';

	$user = $request->input('testkey');

	$result = DB::connection('pgsql')->select(DB::raw("insert into users (deviceuid) values ('".$user."')"));

	echo $user;

	return view('playad', $data);

/* works
	return response()->json([
    'name' => 'Abigail',
    'state' => 'CA'
]);
*/
    }

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

    	$existsflag = 1;
        $rand32 = "";
	$rand64 = generateRandomString(64);
        $model = $request->input('model');

	while($existsflag == 1){
	    $rand32 = generateRandomString(32);
	    $result = DB::table('users')->where('deviceuid', $rand32)->exists();
	    if($result == 0){
	        $result = DB::table('users')->insert(['deviceuid' => $rand32], ['hashkey' => $rand64]);
		$existsflag = 0;
	    }
        }
	
	return response()->json([
	    'deviceuid' => $rand32,
	    'hk' => $rand64
	]);

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
