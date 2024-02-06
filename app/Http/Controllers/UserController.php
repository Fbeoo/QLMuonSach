<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
                'name.required' => 'Tên người dùng không được để trống',

                'dob.required' => 'Ngày sinh không được để trống',
                'dob.date_format' => 'Ngày sinh không đúng định dạng',

                'address.required' => 'Địa chỉ không được để trống'
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

            return response()->json(['success'=>'Sửa thông tin thành công']);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function lockUser(Request $request) {
        try {
            $user = $this->userRepository->find($request->input('id'));

            $user->status = User::statusLock;

            $this->userRepository->update($user);

            return response()->json(['success'=>'Khóa người dùng thành công']);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function unlockUser(Request $request) {
        try {
            $user = $this->userRepository->find($request->input('id'));

            $user->status = User::statusNormal;

            $this->userRepository->update($user);

            return response()->json(['success'=>'Mở khóa người dùng thành công']);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function filterUser(Request $request) {
        try {
            $resultFilter = $this->userRepository->filterUser($request->all());
            return $resultFilter;
        }catch (\Exception $e) {
            dd($e);
            return response()->json(['error'=>$e]);
        }
    }
}
