<?php

namespace App\Http\Controllers\Student\Auth;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use App\Models\PageOption;
use App\Models\Store;
use App\Models\Student;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentLoginController extends Controller
{
    public function __construct()
    {
        $lang = session()->get('lang');
        \App::setLocale(isset($lang) ? $lang : 'en');
    }

    public function showLoginForm($slug)
    {
        $store                 = Store::where('slug', $slug)->first();
        $page_slug_urls        = PageOption::where('store_id', $store->id)->get();
        $blog                  = Blog::where('store_id', $store->id);
        $demoStoreThemeSetting = Utility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }

        // json data
        $getStoreThemeSetting = Utility::getStoreThemeSetting($store->id, $store->theme_dir);
        $getStoreThemeSetting1 = [];

        if(!empty($getStoreThemeSetting['dashboard'])) {
            $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
            $getStoreThemeSetting1 = Utility::getStoreThemeSetting($store->id, $store->theme_dir);
        }
        if (empty($getStoreThemeSetting)) {
            $path = storage_path()."/uploads/" . $store->theme_dir . "/" . $store->theme_dir . ".json" ;
            $getStoreThemeSetting = json_decode(file_get_contents($path), true);
        }

        return view('storefront.' . $store->theme_dir . '.user.login', compact('blog', 'demoStoreThemeSetting', 'store', 'slug', 'page_slug_urls','getStoreThemeSetting','getStoreThemeSetting1'));
    }

    public function login(Request $request, $slug, $cart = 0)
    {
        
        if($this->validator($request, $slug) == true)
        {
            if(Auth::guard('students')->attempt($request->only('email', 'password'), $request->filled('remember')))
            {
                //Authentication passed...
                if($cart == 1)
                {
                    return redirect()->route('store.cart', $slug)->with('success', __('You can checkout now.'));
                }
                else
                {
                    return redirect()->route('student.home', $slug);
                }

            }
        }
        else
        {
            return redirect()->back()->with('error', 'These credentials do not match our records.');
        }


        //Authentication failed...
        return $this->loginFailed();
    }

    private function validator(Request $request, $slug)
    {
        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];
        $validate = Validator::make(
            $request->all(), [
                               'email' => [
                                   'required',
                                   'string',
                                   'email',
                                   'min:5',
                                   'max:191',
                               ],
                               'password' => [
                                   'required',
                                   'string',
                                   'min:4',
                                   'max:255',
                               ],
                           ]
        );
        $store    = Store::where('slug', $slug)->first();
        $vali     = Student::where('email', $request->email)->where('store_id', $store->id)->count();
        if($validate->fails())
        {
            $message = $validate->getMessageBag();

            return redirect()->back()->with('error', $message->first());
        }
        elseif($vali > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function loginFailed()
    {
        return redirect()->back()->withInput()->with('error', 'These credentials do not match our records!');
    }

    public function logout($slug)
    {
        $store          = Store::where('slug', $slug)->first();
        $blog           = Blog::where('store_id', $store->id);
        $page_slug_urls = PageOption::where('store_id', $store->id)->get();
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        Auth::guard('students')->logout();

        return redirect()->route('store.slug', $slug);
    }

    public function profile($slug, $id)
    {
        $store                 = Store::where('slug', $slug)->first();
        $blog                  = Blog::where('store_id', $store->id);
        $page_slug_urls        = PageOption::where('store_id', $store->id)->get();
        $demoStoreThemeSetting = Utility::demoStoreThemeSetting($store->id);
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $userDetail = Student::find(Crypt::decrypt($id));

        return view('storefront.' . $store->theme_dir . '.student.profile', compact('blog', 'demoStoreThemeSetting', 'slug', 'store', 'page_slug_urls', 'userDetail'));
    }

    public function profileUpdate($slug, Request $request)
    {
        $store          = Store::where('slug', $slug)->first();
        $blog           = Blog::where('store_id', $store->id);
        $page_slug_urls = PageOption::where('store_id', $store->id)->get();
        if(empty($store))
        {
            return redirect()->back()->with('error', __('Store not available'));
        }

        $validate = Validator::make(
            $request->all(), [
                               'name' => [
                                   'required',
                                   'string',
                                   'max:255',
                               ],
                               'email' => [
                                   'required',
                                   'string',
                                   'email',
                                   'max:255',
                               ],
                           ]
        );
        $vali     = Student::where('email', $request->email)->where('store_id', $store->id)->count();
        if($validate->fails())
        {
            $message = $validate->getMessageBag();

            return redirect()->back()->with('error', $message->first());
        }
        $student = Student::find($request->id);

        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            // $dir        = storage_path('uploads/profile/');
            // $image_path = $dir . $student['avatar'];

            // if(\File::exists($image_path))
            // {
            //     \File::delete($image_path);
            // }

            // if(!file_exists($dir))
            // {
            //     mkdir($dir, 0777, true);
            // }

            // $path = $request->file('profile')->storeAs('uploads/profile/', $fileNameToStore);

            $settings = Utility::getStorageSetting();
            if($settings['storage_setting']=='local'){
                $dir   = 'uploads/profile/';
            }
            else{
                $dir   = 'uploads/profile/';
            }
            $image_path = $dir . $student['avatar'];
            $path = Utility::upload_file($request,'profile',$fileNameToStore,$dir,[]);
            // dd($path);
            if($path['flag'] == 1){
                $url = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }

        }

        if(!empty($request->profile))
        {
            $student['avatar'] = $fileNameToStore;
        }
        if($request->email != $student->email)
        {
            if($vali > 0)
            {
                return redirect()->back()->with('error', __('Email already exists'));
            }
            else
            {
                $student->email = $request->email;
            }
        }


        $student->name = $request->name;
        $student->update();
        if(!empty($request->current_password) && !empty($request->new_password) && !empty($request->confirm_password))
        {
            if(Auth::guard('students')->check())
            {
                $request->validate(
                    [
                        'current_password' => 'required',
                        'new_password' => 'required|min:6',
                        'confirm_password' => 'required|same:new_password',
                    ]
                );
                $objUser          = Auth::guard('students')->user();
                $request_data     = $request->All();
                $current_password = $objUser->password;
                if(Hash::check($request_data['current_password'], $current_password))
                {
                    $user_id            = Auth::guard('students')->user()->id;
                    $obj_user           = Student::find($user_id);
                    $obj_user->password = Hash::make($request_data['new_password']);;
                    $obj_user->update();

                    return redirect()->back()->with('success', __('Password successfully updated.'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Please enter correct current password.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }


        return redirect()->back()->with('success', __('Profile successfully updated.'));
    }

    public function updatePassword(Request $request)
    {
        if(Auth::guard('students')->check())
        {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser          = Auth::guard('students')->user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if(Hash::check($request_data['current_password'], $current_password))
            {
                $user_id            = Auth::guard('students')->user()->id;
                $obj_user           = Student::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->update();

                return redirect()->back()->with('success', __('Password successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Please enter correct current password.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

}
