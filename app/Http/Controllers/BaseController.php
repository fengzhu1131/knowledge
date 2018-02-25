<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
	const DEFAULT_PAGE_SIZE = 20;
    protected $pageSize;
    protected $validator;
    //use default validate message
    protected $messages = [];
//    [
//        'required' => ':attribute 必须提供.',
//        'integer' => ':attribute 必须为整数类型',
//    ];

    public function __construct()
    {
        $this->pageSize = request('page_size', self::DEFAULT_PAGE_SIZE);
    }

    public function successJson($data = '', $message = '', $extra = [])
    {
        $source = ['success' => 1, 'msg' => $message, 'data' => $data];
		if(!empty(request('callback'))){
			return response()->json(array_merge($source, $extra)) ->setCallback(request('callback'));
		}else{
			return response()->json(array_merge($source, $extra));
		}        
    }

    public function errorJson($message = '', $data = '', $extra = [])
    {
        $source = ['success' => 0, 'msg' => $message, 'data' => $data];
		if(!empty(request('callback'))){
			return response()->json(array_merge($source, $extra)) ->setCallback(request('callback'));
		}else{
			return response()->json(array_merge($source, $extra));
		}         
    }

    public function validateRequest(array $data, array $rules, array $customAttributes = [])
    {
        $this->validator = Validator::make($data, $rules, $this->messages, $customAttributes);

        return !$this->validator->fails();
    }

    public function getValidateMessage()
    {
        return $this->validator->errors();
    }
}
