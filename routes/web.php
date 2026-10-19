<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $cases = \App\Models\ChildCase::with('events')->latest()->get();
    return view('dashboard', compact('cases'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/cases', function (\Illuminate\Http\Request $request, \App\Services\CaseStateMachineService $stateMachine) {
    $validated = $request->validate([
        'minor_name' => 'required|string|max:255',
        'phone_number' => 'nullable|string|max:20',
        'family_details' => 'nullable|string',
    ]);

    $case = new \App\Models\ChildCase($validated);
    $case->district_id = auth()->user()->district_id; // Will be null for now, or fallback
    if (!$case->district_id) {
        // Fallback for MVP if user has no district
        $district = \App\Models\District::firstOrCreate(['name' => 'Default District', 'region' => 'Global']);
        $case->district_id = $district->id;
    }
    $case->state = 'Received';
    $case->save();

    // Log the event via State Machine
    // Since it's a new case, we use a generic payload or we just log a state transition
    \App\Models\CaseEvent::create([
        'child_case_id' => $case->id,
        'user_id' => auth()->id(),
        'from_state' => null,
        'to_state' => 'Received',
        'event_type' => 'case_created',
        'payload' => json_encode(['source' => 'dashboard_modal'])
    ]);

    // Dispatch Linear issue
    $linear = new \App\Services\LinearService();
    $linear->dispatchIssue([
        'title' => 'New Case Received: ' . substr($case->id, 0, 8),
        'description' => 'A new case has been securely logged.'
    ]);

    // Track event
    $posthog = new \App\Services\PostHogService();
    $posthog->captureEvent(auth()->id(), 'case_created');

    return redirect()->route('dashboard')->with('status', 'Case secured.');
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
