<?php

namespace App\Http\Controllers;


use Illuminate\Validation\ValidationException;
use Validator;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController {

    public function validate(Request $request, array $rules=array(), array $messages=array(), array $customAttributes=array()) {

        $validator = Validator::make($request->get('data')['attributes'], $rules);

        if($validator->fails()) {
            $this->validator_errors($validator);
        } else {
            return $validator;
        }

    }

    public function validator_errors($validator) {
        $errors = $validator->errors()->all();

        for($i = 0; $i < count($errors); $i++) {
            $errors[$i] = array('details' => $errors[$i]);
        }

        throw new ValidationException($validator, response()->json(['errors' => $errors], 422));
    }

    public function JSONFormat($items, $type) {
        $new_items = [];
        for($i = 0; $i < count($items); $i++) {
            $new_items[$i]['id'] = $items[$i]['id'];
            $new_items[$i]['attributes'] = $items[$i];
            $new_items[$i]['type'] = $type;
        }
        return $new_items;
    }

}
