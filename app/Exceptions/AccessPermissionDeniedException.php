<?php

namespace App\Exceptions;

use Exception;

class AccessPermissionDeniedException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'alert-type' => 'error',
                'message' => __('Permission Denied, You can not perform this action!'),
            ], 403);
        }

        return redirect()->back()->with([
            'alert-type' => 'error',
            'message' => __('Permission Denied, You can not perform this action!'),
        ]);
    }
}
