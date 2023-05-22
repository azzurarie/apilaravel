<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'image' => 'nullable|image:jpeg,png,jpg,gif,svg',
            'username' => 'required|string|max:15|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
            'confirm_password' => 'required|same:password',
            'gender'=>'required|string|max:1',
            'birth_of_date'=>'required|date',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'image'=>$request->image,
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender'=>$request->gender,
            'birth_of_date'=>$request->birth_of_date,
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->username.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
    
    public function update($id, Request $request)
    {
        $validasi = $request->validate([
            'image' => 'nullable|image:jpeg,png,jpg,gif,svg',
            'username'      => 'required',
            'name'      => 'required',
            'email'     => 'required|email',
            // |unique:users',
            'password'  => 'required|min:5',
            'confirm_password' => 'required|same:password',
            'gender'=>'required|string',
            'birth_of_date'=>'required|date',
        ]);
    
        //melakukan update data berdasarkan id
        $user              = User::find($id);
        $user->image      = $request->image;
        $user->username    = $request->username;
        $user->name        = $request->name;
        $user->email       = $request->email;
            
        //password di-hash agar tidak terbaca
        $user->password    = Hash::make($request->password);
        $user->gender      = $request->gender;
        $user->birth_of_date      = $request->birth_of_date;

        
        //jika berhasil maka simpan data dengan method $post->save()
        if($user->save()){
            return response()->json( 'Post Berhasil Disimpan');
        }else{
            return response()->json('Post Gagal Disimpan');
        }
    }

    public function delete($id){
        //mencari data sesuai $id
        //$id diambil dari ujung url yang kita kirimkan dengan postman
        $user = User::findOrFail($id);
        
        // jika data berhasil didelete maka tampilkan pesan json 
        if($user->delete()){
            return response([
                'Berhasil Menghapus Data'
            ]);
        }else{
            //response jika gagal menghapus
            return response([
                'Tidak Berhasil Menghapus Data'
            ]);
        }
    }
    
}
