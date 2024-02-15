<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use MongoDB\Driver\Session;

class UserController extends Controller
{

    protected $userRepository;

    /**
     * @param $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request) {
        try {
            $validation = Validator::make($request->all(),[
                'name' => 'required',
                'mail' => 'required|email',
                'address' => 'required',
                'password' => 'required',
                'confirmPassword' => 'required'
            ],[
                'name.required' => @trans('message.nameRequired'),

                'mail.required' => @trans('message.mailRequired'),
                'mail.email' => @trans('message.mailEmail'),

                'address.required' => @trans('message.addressRequired'),

                'password.required' => @trans('message.passwordRequired'),

                'confirmPassword.required' => @trans('message.confirmPasswordRequired'),
            ]);

            if ($request->input('password') !== $request->input('confirmPassword')) {
                $validation->errors()->add('confirmPassword',@trans('message.passwordNotMatch'));
            }

            $checkEmailExist = $this->userRepository->getUserByMail($request->input('mail'));
            if ($checkEmailExist) {
                $validation->errors()->add('mail',@trans('message.mailExist'));
            }

            if (count($validation->errors()) > 0) {
                return view('register',['error'=>$validation->errors()]);
            }

            $user = new User();

            $user->name = $request->input('name');
            $user->mail = $request->input('mail');
            $user->password = bcrypt($request->input('password'));
            $user->address = $request->input('address');
            $user->remember_token = Str::random(40);
            $user->status = User::statusInactive;

            $userRegisterId = $this->userRepository->add($user);

            Mail::send('verifyEmail',compact('user'),function ($message) use($user){
                $message->subject(@trans('message.verifyEmail'));
                $message->to($user->mail);
            });

            return view('noticeVerifyEmail',['user'=>$user]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function verifyEmail($token) {
        try {
            $user = $this->userRepository->getUserByToken($token);
            if ($user) {
                $user[0]->status = User::statusNormal;
                $user[0]->remember_token = '';
                $this->userRepository->update($user[0]);
                return redirect('login')->with('success',@trans('message.activeAccountSuccessfully'));
            }else {
                return view('error.404');
            }
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function resendVerifyEmail(Request $request) {
        try {
            $user = $this->userRepository->getUserByMail($request->input('mail'))[0];
            if ($user) {
                $user->remember_token = Str::random(40);
                $this->userRepository->update($user);
                Mail::send('verifyEmail',compact('user'),function ($message) use($user){
                    $message->subject(@trans('message.verifyEmail'));
                    $message->to($user->mail);
                });
                return view('noticeVerifyEmail',['user'=>$user,'success'=>@trans('message.resendVerifyEmailSuccessfully')]);
            }
            else {
                return view('404');
            }
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function getAllUser() {
        try {
            $users = $this->userRepository->getAllUser();
            return $users;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function showAllUser() {
        try {
            $users = $this->userRepository->getAllUser();
            return view('admin.manageUser',['users'=>$users]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function editProfile(Request $request) {
        try {
            $validation = Validator::make($request->all(),[
                'name' => 'required',
                'dob' => 'required|date_format:d/m/Y',
                'address' => 'required'
            ],[
                'name.required' => @trans('message.nameRequired'),

                'dob.required' => @trans('message.dobRequired'),
                'dob.date_format' => @trans('message.dobDateFormat'),

                'address.required' => @trans('message.addressRequired')
            ]);

            if ($validation->fails()) {
                return response()->json(['errorValidate'=>$validation->errors()]);
            }

            $user = $this->userRepository->find($request->input('userId'));

            $user->name = $request->input('name');

            $dob = Carbon::createFromFormat('d/m/Y',$request->input('dob'))->format('Y/m/d');
            $user->dob = $dob;

            $user->address = $request->input('address');

            $this->userRepository->update($user);

            \session()->put('user',$user);

            return response()->json(['success'=>@trans('message.updateInformationSuccessfully')]);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function lockUser(Request $request) {
        try {
            $user = $this->userRepository->find($request->input('id'));

            $user->status = User::statusLock;

            $this->userRepository->update($user);

            return response()->json(['success'=>@trans('message.lockUserSuccessfully')]);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function unlockUser(Request $request) {
        try {
            $user = $this->userRepository->find($request->input('id'));

            $user->status = User::statusNormal;

            $this->userRepository->update($user);

            return response()->json(['success'=>@trans('message.unlockUserSuccessfully')]);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function filterUser(Request $request) {
        try {
            $resultFilter = $this->userRepository->filterUser($request->all());
            return $resultFilter;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }
}
