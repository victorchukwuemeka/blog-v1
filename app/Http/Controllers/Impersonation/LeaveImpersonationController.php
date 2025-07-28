<?php

namespace App\Http\Controllers\Impersonation;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Lab404\Impersonate\Services\ImpersonateManager;

class LeaveImpersonationController extends Controller
{
    public function __invoke(ImpersonateManager $impersonate) : RedirectResponse
    {
        if ($impersonate->isImpersonating()) {
            $impersonate->leave();
        }

        return redirect()->route('filament.admin.resources.users.index');
    }
}
