<?php

namespace App\Http\Controllers;

use App\Models\Intakes;
use App\Models\Order;
use App\Models\Pills;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Input;
use Validator;
use JWTAuth;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class APIController extends Controller
{

    public function register(Request $request)
    {
//        die("ladida");
        $input = $request->all();
        $validator = Validator::make($input, array(
            'password' => 'required',
            'name' => 'required|unique:users',
        ));

        if ($validator->passes()) {
            $user = User::create($input);
            return response()->json(['status' => 1, 'id' => $user->id]);
        } else {
            return response()->json(array('status' => 0, 'errors' => $validator->errors()->first()));
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['status' => 0, 'error' => 'wrong name or password.']);
        } else {
            $pills = array();
            foreach (Auth::user()->pills()->get() as $pill) {

                $pills[] = array(
                    'id' => $pill->id,
                    'name' => $pill->name,
                    'description' => $pill->description,
                    'numberOfIntakes' => $pill->numberOfIntakes,
                    'intakes' => $pill->intakes()->get()
                );
            }
            return response()->json(['jwt' => $token, 'pills' => $pills]);
        }
    }

    public function createPill(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, array(
            'name' => 'required',
            'description' => 'required',
            'intakes' => 'required|array',
            'intakes.*.timeOfIntake' => 'required|date_format:H:i:s'
        ));
        if ($validator->passes()) {

            $pill = new Pills($input);
            $pill->user()->associate(Auth::user());
            $pill->numberOfIntakes = count($input['intakes']);
            $pill->save();

            foreach ($input['intakes'] as $intakeArray) {
                $intakeModel = new Intakes($intakeArray);
                $intakeModel->pill()->associate($pill);
                $intakeModel->save();
            }
            return response()->json(['status' => 1, 'id' => $pill->id]);
        } else {
            return response()->json(array('status' => 0, 'errors' => $validator->errors()->first()));
        }
    }

    public function deletePill(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, array(
            'id' => 'required|exists:pills,id'
        ));

        if ($validator->passes()) {
            $pill = Pills::where('id', $input['id'])->where('id_user', Auth::user()->id)->first();
            if($pill != null) {
                $pill->delete();
                return response()->json(['status' => 1]);
            } else {
                return response()->json(array('status' => 0, 'errors' => 'Not permitted'));
            }
        } else {
            return response()->json(array('status' => 0, 'errors' => $validator->errors()->first()));
        }
    }

    public function updatePill(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, array(
            'id' => 'required|exists:pills',
            'name' => 'required',
            'description' => 'required',
            'intakes' => 'required|array',
            'intakes.*.timeOfIntake' => 'required|date_format:H:i:s'
        ));

        if ($validator->passes()) {
            $pill = Pills::findOrFail($input['id']);
            $pill->name = $input['name'];
            $pill->description = $input['description'];
            $pill->numberOfIntakes = count($input['intakes']);

            $pill->intakes()->delete();
            foreach ($input['intakes'] as $intakeArray) {
                $intakeModel = new Intakes($intakeArray);
                $intakeModel->pill()->associate($pill);
                $intakeModel->save();
            }

            $pill->save();
            return response()->json(['status' => 1]);
        } else {
            return response()->json(array('status' => 0, 'errors' => $validator->errors()->first()));
        }
    }
}
