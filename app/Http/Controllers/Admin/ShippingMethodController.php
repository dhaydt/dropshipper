<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\Http\Controllers\Controller;
use App\Imports\BranchImport;
use App\Imports\DestImport;
use App\Imports\OriginImport;
use App\JneBranch;
use App\JneOrigin;
use App\Model\ShippingMethod;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\BusinessSetting;
use Maatwebsite\Excel\Facades\Excel;

class ShippingMethodController extends Controller
{
    public function jne_config(){
        $check = BusinessSetting::where('type', 'jne_config')->first();
        if(!$check){
            $check = new BusinessSetting();
            $check->type = 'jne_config';
            $check->value = json_encode([
                "branch" => '',
                "origin" => ''
            ]);
            $check->save();
        }

        $check = json_decode($check->value);
        $data['o_branch'] = $check->branch;
        $data['o_origin'] = $check->origin;



        $data['branch'] = JneBranch::all();
        $data['origin'] = JneOrigin::all();
        return view('admin-views.shipping-method.jne_config', $data);
    }
    public function jne_config_store(Request $request){
        // dd($request);
        if($request->has('branch')){
            // dd($request->branch);
            $branch = $request->file('branch');
            $branch_name = rand().$branch->getClientOriginalName();

            $branch->move('branch',$branch_name);
            Excel::import(new BranchImport, public_path('/branch/'.$branch_name));

        }
        if($request->has('dest')){
            // dd($request->branch);
            $branch = $request->file('dest');
            $branch_name = rand().$branch->getClientOriginalName();

            $branch->move('branch',$branch_name);
            Excel::import(new DestImport, public_path('/branch/'.$branch_name));
        }
        
        if($request->has('origin')){
            // dd($request->branch);
            $branch = $request->file('origin');
            $branch_name = rand().$branch->getClientOriginalName();

            $branch->move('branch',$branch_name);
            Excel::import(new OriginImport, public_path('/branch/'.$branch_name));
        }

        // $branch->move('branch',$branch_name);
        // Excel::import(new BranchImport, public_path('/branch/'.$branch_name));
        Toastr::success('Branch imported successfully');
        return redirect()->back();
    }
    public function jne_config_store_setting(Request $request){
        $request->validate([
            'branch'    => 'required',
            'origin' => 'required',
        ]);
        
        $check = BusinessSetting::where('type', 'jne_config')->first();

        $check->value = json_encode([
            'branch' => $request->branch,
            'origin' => $request->origin,
        ]);
        $check->save();
        Toastr::success('JNE Config saved successfully!');
        return redirect()->back();
    }
    public function index_admin()
    {
        $shipping_methods = ShippingMethod::where(['creator_type' => 'admin'])->get();
        return view('admin-views.shipping-method.add-new', compact('shipping_methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|max:200',
            'duration' => 'required',
            'cost'     => 'numeric',
        ]);

        DB::table('shipping_methods')->insert([
            'creator_id'   => auth('admin')->id(),
            'creator_type' => 'admin',
            'title'        => $request['title'],
            'duration'     => $request['duration'],
            'cost'         => BackEndHelper::currency_to_usd($request['cost']),
            'status'       => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        Toastr::success('Successfully added.');
        return back();
    }

    public function status_update(Request $request)
    {
        ShippingMethod::where(['id' => $request['id']])->update([
            'status' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function edit($id)
    {
        if ($id != 1) {
            $method = ShippingMethod::where(['id' => $id])->first();
            return view('admin-views.shipping-method.edit', compact('method'));
        }
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'    => 'required|max:200',
            'duration' => 'required',
            'cost'     => 'numeric',
        ]);

        DB::table('shipping_methods')->where(['id' => $id])->update([
            'creator_id'   => auth('admin')->id(),
            'creator_type' => 'admin',
            'title'        => $request['title'],
            'duration'     => $request['duration'],
            'cost'         => BackEndHelper::currency_to_usd($request['cost']),
            'status'       => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        Toastr::success('Successfully updated.');
        return redirect()->back();
    }

    public function setting()
    {
        return view('admin-views.shipping-method.setting');
    }
    public function shippingStore(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'shipping_method'], [
            'value' => $request['shippingMethod']
        ]);
        Toastr::success('Shipping Method Added Successfully!');
        return back();
    }
    public function delete(Request $request)
    {

        $shipping = ShippingMethod::find($request->id);

        $shipping->delete();
        return response()->json();
    }

}
