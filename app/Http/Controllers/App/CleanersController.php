<?php

namespace App\Http\Controllers\App;

use App\Base\Models\Cleaner;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCleanerRequest;
use Illuminate\Http\RedirectResponse;

class CleanersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     *
     */
    public function index()
    {
        return view('app.cleaners')
            ->with('cleaners', auth()->user()->cleaners);
    }

    /**
     * Store a new cleaner.
     *
     * @param \App\Http\Requests\CreateCleanerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCleanerRequest $request): RedirectResponse
    {
        tap(new Cleaner($request->validated()), function (Cleaner $cleaner) {
            $cleaner->user()->associate(auth()->user());
            $cleaner->save();
        });

        return redirect()->route('app.cleaners.index');
    }

    /**
     * Remove a cleaner.
     *
     * @param \App\Base\Models\Cleaner $cleaner
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Cleaner $cleaner): RedirectResponse
    {
        $cleaner->delete();

        return redirect()->route('app.cleaners.index');
    }
}
