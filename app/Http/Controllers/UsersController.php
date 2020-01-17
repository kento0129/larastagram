<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Follower;
use App\User;
use App\Rules\AlphaNumeric;
use App\Rules\DisagreementPassword;
use App\Rules\OldPassword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show($user_id)
    {
        $user = User::where('id', $user_id)
                      ->firstOrFail();
                      
        $follower = Follower::where('following_id',Auth::user()->id)
                            ->where('followed_id',$user_id)
                            ->first();
            
        return view('user/show', compact('user','follower'));
    }
    
    public function edit()
    {
        $user = Auth::user();
        
        return view('user/edit',['user' => $user]);
    }
    
    public function password()
    {
        $user = Auth::user();
        
        return view('user/password',['user' => $user]);
    }
    
     public function update(Request $request)
    {
        $user = User::find($request->id);
        //バリデーション（入力値チェック）
        $validator = Validator::make($request->all() , [
            'name' => ['required','string','max:30'],
            'user_name' =>['required','string','min:4','max:30','unique:users,user_name,'.$user->user_name.',user_name',new AlphaNumeric],
            'email' => ['required','string','email','max:255','unique:users,email,'.$user->email.',email'],
            'profile_photo' => ['file','image','mimes:jpeg,png,jpg,gif'],
        ]);

        //バリデーションの結果がエラーの場合
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        
        $user->name = $request->name;
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        
        $filename = $request->file('profile_photo');

        //既にプロフィール写真が登録されている場合削除
        if($user->profile_photo != null && $request->profile_photo != null)
        {
            // テスト環境, ローカル環境用の記述
            if (app()->isLocal() || app()->runningUnitTests())
            {
                Storage::disk('public')->delete('user_images/'.$user->profile_photo);
            }
            // 本番環境用の記述
            else
            {
                $delete_path = strstr($user->profile_photo, 'user_images/');
                Storage::disk('s3')->delete($delete_path);
            }
        }
        
        //プロフィール写真の登録
        if ($request->profile_photo !=null)
        {
            $filename = $user->id . '_' . 'user_' . date('YmdHis') . '.' . $request->profile_photo->getClientOriginalExtension();
            $image = $request->file('profile_photo');

            // テスト環境, ローカル環境用の記述
            if (app()->isLocal() || app()->runningUnitTests())
            {
                Storage::disk('public')->putFileAs('user_images', $image, $filename ,'public');
                $user->profile_photo = $filename;
            }
            // 本番環境用の記述
            else
            {
                $path = Storage::disk('s3')->putFileAs('user_images', $image, $filename ,'public');
                $user->profile_photo = Storage::disk('s3')->url($path);
            }
        }
        $user->save();

        return redirect('/users/'.$request->id);
    }
    
    public function change(Request $request){
        $user = User::find($request->id);
        
        //バリデーション（入力値チェック）
        $validator = Validator::make($request->all() , [
            'old_password' => ['required',new OldPassword],
            'new_password' => ['required','string','min:6','confirmed',new DisagreementPassword], 
            'new_password_confirmation' => ['required'], 
        ]);

        //バリデーションの結果がエラーの場合
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        
        $user->password = bcrypt($request->new_password);
        $user->save();
        
        return redirect('/users/'.$request->id);
    }
}
