<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiController extends BaseController
{
    public function testcontroller($id)
    {
        echo 'id is:' . $id;
        echo '<br>';

	$data['id'] = $id;
        return view('test', $data);
    }
}
