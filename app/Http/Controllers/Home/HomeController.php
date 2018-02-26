<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\BaseController;
use Log;


/**
 * todo controller example
 * Class NotesController
 * @package App\Http\Controllers\Home
 */
class HomeController extends BaseController {
	public function getIndex() {
		return view('index');
	}
}
