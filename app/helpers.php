<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;
use App\Models\User;

if (!function_exists('flash_notification')) {
    /**
     * Set a flash notification message to be displayed using Toastr.
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    function flash_notification($message, $type = 'success')
    {
        $validTypes = ['success', 'danger', 'warning', 'info'];

        if (!in_array($type, $validTypes)) {
            $type = 'success'; // Default type
        }

        Session::flash('flash_notification', [
            'message' => $message,
            'type' => $type,
        ]);
    }
}
