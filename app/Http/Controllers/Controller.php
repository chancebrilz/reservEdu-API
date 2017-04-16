<?php

namespace App\Http\Controllers;


use Illuminate\Validation\ValidationException;
use Validator;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController {

    public function validate(Request $request, array $rules=array(), array $messages=array(), array $customAttributes=array()) {
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        return ($validator->fails()) ? $this->validator_errors($validator) : $validator;
    }

    public function validator_errors($validator) {
        $errors = $validator->errors()->all();

        for($i = 0; $i < count($errors); $i++) {
            $errors[$i] = array('details' => $errors[$i]);
        }

        throw new ValidationException($validator, response()->json(['errors' => $errors], 422));
    }

}
