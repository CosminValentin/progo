<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait HandlesModal
{
    /**
     * Si la petición viene desde modal (?modal=1), cierra modal.
     * Si existe ?return_to, redirige allí.
     * Si no, redirect()->back().
     */
    protected function modalRedirect(Request $request, $fallback = null)
    {
        if ($request->boolean('modal')) {
            return response()->view('components.modal-close');
        }

        $returnTo = $request->input('return_to');
        if ($returnTo) {
            return redirect()->to($returnTo);
        }

        if ($fallback) {
            return redirect()->to($fallback);
        }

        return back();
    }
}
