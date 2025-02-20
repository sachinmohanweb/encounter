<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use DB;
use Exception;

use App\Models\AppBanner;

class AppBannerController extends Controller
{
    
    public function App_Banners() : View
    {
        $banners = AppBanner::get();

        return view('app_banners.Bannerslist',compact('banners'));
    }
    
    public function Store_App_Banner(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $a =  $request->validate([
                'title' => 'required',
                'file' => 'required|file|max:500',
            ]);

            $inputData = $request->all();

            if($request['file']){

                $fileName ='app_banners_' . time() . '.' .$request['file']->extension();
                $request->file->storeAs('app_banners', $fileName);
                $inputData['path'] = 'storage/app_banners/'.$fileName;
            }

            $banner = AppBanner::create($inputData);
            DB::commit();
             
            return redirect()->route('admin.app_banners.list')
                ->with('success',"Success! New banner has been successfully added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function BannerStatusChange(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $banner = AppBanner::find($request['id']);
            if($banner->status=='Active'){
                $banner->status=1;
            }else{
                $banner->status=2;
            }
            $banner->save();;
            DB::commit();

            return response()->json(['success' => true ,'msg' => 'banner status updated','status' =>$banner['status']]);

        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();

            return response()->json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    public function Delete_App_Banners(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $banner =AppBanner::where('id',$request->id)->first();
            if($banner){
                $banner->delete();
                DB::commit();
                $return['status'] = "success";
            }else{
                $return['status'] = 'failed';
            }

         }catch (Exception $e) {

            DB::rollBack();
            $return['status'] = $e->getMessage();
        }
        return response()->json($return);
    }
 
}
