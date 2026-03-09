<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\CustomMenu\app\Enums\AllMenus;

class MenuController extends Controller
{
    public function mainMenu(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $mainMenu = menuGetBySlugAndLang(AllMenus::MAIN_MENU,$code);

        if ($mainMenu) {
            return response()->json(['status' => 'success', 'data' => $mainMenu], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function footerMenuOne(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $mainMenu = menuGetBySlugAndLang(AllMenus::FOOTER_ONE_MENU,$code);

        if ($mainMenu) {
            return response()->json(['status' => 'success', 'data' => $mainMenu], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function footerMenuTwo(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $mainMenu = menuGetBySlugAndLang(AllMenus::FOOTER_TWO_MENU,$code);

        if ($mainMenu) {
            return response()->json(['status' => 'success', 'data' => $mainMenu], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
}
