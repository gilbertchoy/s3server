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
        $model = $request->input('model');
	//echo "model: " . $model;
	$data['model'] = 'this is model';

	$name = $request->input('testkey');

	echo $name;

	return view('playad', $data);

/* works
	return response()->json([
    'name' => 'Abigail',
    'state' => 'CA'
]);
*/

    }


    public function testcontroller($id)
    {
        echo 'id is:' . $id;
        echo '<br>';

	$data['id'] = $id;

	//$users = DB::select ('select * from users');
	//$users = DB::connection('pgsql')->select('select * from users');
	$result = DB::connection('pgsql')->select(DB::raw("select * from users"));



	print_r($result);

	
        return view('test', $data);
    }
}
