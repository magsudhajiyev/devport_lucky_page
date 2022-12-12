<?php

namespace App\Http\Controllers;

use App\Models\LuckyNumberResult;
use Illuminate\Http\Request;
use App\Services\UniqueLinkService;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class LuckyPageController extends Controller
{
    protected $linkService;

    public function __construct(UniqueLinkService $linkService)
    {
        $this->linkService = $linkService;
    }
    public function index(Request $request)
    {
        if (!$this->linkService->checkToken($request->user->unique_link)) {
           abort(403);
        }

        $pageData = [
            'unique_link' => Config::get('app.url') . "/lucky_page/" . $request->user->unique_link,
            'token' => $request->user->unique_link
        ];

        return view('lucky_page')->with($pageData);
    }

    public function new_link(Request $request): string
    {
        $newLink =  $this->linkService->generateToken($request->user->id);

        $request->user->unique_link = $newLink;
        $request->user->save();
        return Config::get('app.url') . "/lucky_page/" . $newLink;
    }

    public function deactivate(Request $request): JsonResponse
    {
        $request->user->unique_link = null;
        $request->user->save();
        
        return response()->json(['code' => 'ok']);
    }

    public function history(Request $request): JsonResponse
    {
        $result =  LuckyNumberResult::where('user_id', $request->user->id)->latest()->take(3)->get();

        if (count($result) == 0) {
            return response()->json(['message' => 'There is no any result', 'code' => 'empty']);
        }

        return response()->json(['data' => $result, 'code' => 'ok']);
    }

    public function random(Request $request): Collection
    {
        $luckyNumber = $request->route('luckyNumber');
        $winningAmount = 0;

        if ($luckyNumber % 2 == 0) {
            $winningAmount = $this->getWinningAmount($luckyNumber);
        }

        $request->user->lucky_number_results()->create([
            'user_id' => $request->user->id,
            'lucky_number' => $luckyNumber,
            'winning_amount' => $winningAmount
        ]);

        return $request->user->lucky_number_results;
    }

    private function getWinningAmount($luckyNumber): float
    {
        if ($luckyNumber > 900) {
            return $luckyNumber * 0.7;
        }

        if ($luckyNumber > 600 && $luckyNumber <= 900) {
            return $luckyNumber * 0.5;
        }

        if ($luckyNumber > 300 && $luckyNumber <= 600) {
            return $luckyNumber * 0.3;
        }

        if ($luckyNumber <= 300) {
            return $luckyNumber * 0.1;
        }
    }
}
