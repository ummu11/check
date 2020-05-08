<?php

namespace App\Http\Controllers\LoginReg;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\AuthModel;
use App\models\FileModel;
use App\DataTables;
use Validator;

class LoginRegController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function Log(Request $request)
    {
        if(request()->ajax()){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if ($validator->fails()) {


			return response()->json(['error'=>$validator->errors()]); 
        }
        else{
            return response()->json(['success'=>'true']);
        }
    }
        $email=$request->input('email');
        $pass=$request->input('password');
        $us = AuthModel::where('email', $email)->first();
        if($pass==$us->password){
            $id=$us->id;
           
            $secdata = FileModel::find($id);
            
            $image=$secdata->photo;
            
             $fullname=$us->firstname." ".$us->lastname;
                $data=array($fullname,$image);
                
             $request->session()->put("data",$data);
            
             return view('website.UserProfile');
             
        }
        else{
            return view('website.Home');
        }


}

    public function dataTab(Request $request)
    {  if($request->ajax()){
        $data=AuthModel::all();

        return \DataTables::of($data)->addColumn('action', function($data){
            $button = "<button type='button' id='$data->id' class='btn btn-primary edit'>
            Edit
          </button>";
            $button .= "&nbsp;<button type='button' id='$data->id' class='btn btn-primary delete'>
            delete
          </button>";
            return $button;
        })
        ->rawColumns(['action'])->make(true);
    }
    //return redirect('/log');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Reg(Request $request)
    {     
        if(request()->ajax()){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'firstname' => 'required',
            'lastname' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'file' => 'required',
            'phone' => 'required',
            'confirmpassword' => 'required|same:password',
            

        ]);
        if ($validator->fails()) {


			return response()->json(['error'=>$validator->errors()]); 
        }
       else{
        return response()->json(['success'=> 'true']);  
       }
    }
             $file=$request->file('file');
            $name =time().time().'.'.$file->getClientOriginalExtension();  
            $target_path    =   public_path('/userimages/');
             $file->move($target_path, $name);
             $obj=new AuthModel();
             $obj1=new FileModel();
             $obj->firstname=$request->input('firstname');
              $obj->lastname=$request->input('lastname');
              $obj->email=$request->input('email');
              $obj->password=$request->input('password');
             $obj->dob= $request->input('dob');
             $obj->gender= $request->input('gender');
              $obj->status='A';
              $obj1->photo= $name;
             $obj1->phone= $request->input('phone');
              $obj->save();
             $obj1->save();
             return view('website.Home');
                


    }

    public function check(Request $request)
    {
        $da=Session::get('data');
        if(empty($da)){
            return view('website.Home');
        }
        else{
            return redirect('/usergo');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
    }
}
