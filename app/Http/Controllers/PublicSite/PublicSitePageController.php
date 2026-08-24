<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicSitePageController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('PublicSite/Home');
    }

    public function about(): Response
    {
        return Inertia::render('PublicSite/About');
    }

    public function academics(): Response
    {
        return Inertia::render('PublicSite/Academics');
    }

    public function admissions(): Response
    {
        return Inertia::render('PublicSite/Admissions');
    }

    public function campus(): Response
    {
        return Inertia::render('PublicSite/CampusLife');
    }

    public function news(): Response
    {
        return Inertia::render('PublicSite/NewsEvents');
    }

    public function contact(): Response
    {
        return Inertia::render('PublicSite/Contact');
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return back()->with(
            'success',
            'Thank you. Your message has been received. The relevant office will respond during working hours.'
        );
    }
}
