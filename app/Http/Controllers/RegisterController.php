<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\UniqueLinkService;

class RegisterController extends Controller
{
    protected $linkService;

    public function __construct(UniqueLinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    public function index(RegisterRequest $request)
    {
        //Transaction should be added here
        try {
            $user = User::create($request->all());

            if ($user) {
                $user->unique_link = $this->linkService->generateToken($user->id);
                $user->save();

                return redirect()->route('lucky_page', ['token' => $user->unique_link]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
