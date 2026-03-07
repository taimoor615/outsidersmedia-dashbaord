<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\TeamMemberInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class TeamController extends Controller
{
    /**
     * Display a listing of team members
     */
    public function index()
    {
        $teamMembers = User::team()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.team.index', compact('teamMembers'));
    }

    /**
     * Show the form for creating a new team member
     */
    public function create()
    {
        $timezones = timezone_identifiers_list();
        return view('admin.team.create', compact('timezones'));
    }

    /**
     * Store a newly created team member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'timezone' => 'required|string',
        ]);

        // Create team member with temporary password
        $temporaryPassword = 'temp' . rand(100000, 999999);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($temporaryPassword),
            'role' => 'team',
            'status' => 'inactive', // Will be activated after email verification
            'timezone' => $validated['timezone'],
            'is_verified' => false,
        ]);

        // Generate verification token
        $token = $user->generateVerificationToken();
        $verificationUrl = route('verify.email', ['token' => $token]);

        // Send invitation email
        try {
            Mail::to($user->email)->send(new TeamMemberInvitation($user, $verificationUrl));

            return redirect()
                ->route('admin.team.index')
                ->with('success', "Team member {$user->name} has been created. An activation email has been sent to {$user->email}");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.team.index')
                ->with('warning', "Team member created but email could not be sent. Error: {$e->getMessage()}");
        }
    }

    /**
     * Display the specified team member
     */
    public function show(User $team)
    {
        if (!$team->isTeam()) {
            abort(404, 'Team member not found');
        }

        return view('admin.team.show', compact('team'));
    }

    /**
     * Show the form for editing team member
     */
    public function edit(User $team)
    {
        if (!$team->isTeam()) {
            abort(404, 'Team member not found');
        }

        $timezones = timezone_identifiers_list();
        return view('admin.team.edit', compact('team', 'timezones'));
    }

    /**
     * Update the specified team member
     */
    public function update(Request $request, User $team)
    {
        if (!$team->isTeam()) {
            abort(404, 'Team member not found');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $team->id,
            'timezone' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $team->update($validated);

        return redirect()
            ->route('admin.team.index')
            ->with('success', "Team member {$team->name} has been updated successfully");
    }

    /**
     * Remove the specified team member
     */
    public function destroy(User $team)
    {
        if (!$team->isTeam()) {
            abort(404, 'Team member not found');
        }

        $name = $team->name;
        $team->delete();

        return redirect()
            ->route('admin.team.index')
            ->with('success', "Team member {$name} has been deleted successfully");
    }

    /**
     * Resend verification email
     */
    public function resendVerification(User $team)
    {
        if (!$team->isTeam()) {
            abort(404, 'Team member not found');
        }

        if ($team->isVerified()) {
            return redirect()
                ->route('admin.team.index')
                ->with('info', "{$team->name} is already verified");
        }

        // Generate new verification token
        $token = $team->generateVerificationToken();
        $verificationUrl = route('verify.email', ['token' => $token]);

        try {
            Mail::to($team->email)->send(new TeamMemberInvitation($team, $verificationUrl));

            return redirect()
                ->route('admin.team.index')
                ->with('success', "Verification email has been resent to {$team->email}");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.team.index')
                ->with('error', "Failed to send email: {$e->getMessage()}");
        }
    }

    /**
     * Toggle team member status
     */
    public function toggleStatus(User $team)
    {
        if (!$team->isTeam()) {
            abort(404, 'Team member not found');
        }

        $newStatus = $team->status === 'active' ? 'inactive' : 'active';
        $team->update(['status' => $newStatus]);

        return redirect()
            ->route('admin.team.index')
            ->with('success', "Team member {$team->name} is now {$newStatus}");
    }
}
