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
        $model = $request->input('model');

	//$result = DB::table('users')->insert(['deviceuid' => $model]);
	$result = DB::table('users')->insert(var_dump($request));	

	print_r($result);
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
