<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function index()
    {
        $this->ensureSuperAdmin();

        return Inertia::render('ActivityLog/Index', [
            'activities' => Activity::with('causer')
                ->latest()
                ->paginate(8),
        ]);
    }

    public function show($id)
    {
        $this->ensureSuperAdmin();

        $activity = Activity::with('causer')->findOrFail($id);

        return Inertia::render('ActivityLog/Show', [
            'activity' => $activity,
        ]);
    }

    public function getLogs($module, $id)
    {
        $this->ensureSuperAdmin();

        $modelClass = Relation::getMorphedModel($module);
        if (! $modelClass) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(
            Activity::with('causer')
                ->where('subject_type', $modelClass)
                ->where('subject_id', $id)
                ->latest()
                ->get()
        );
    }

    public function destroy($id)
    {
        $this->ensureSuperAdmin();

        $activity = Activity::findOrFail($id);
        $activity->delete();

        return back()->with('danger', 'Activity log deleted successfully.');
    }

    public function clearAll()
    {
        $this->ensureSuperAdmin();

        Activity::query()->delete();

        return back()->with('danger', 'All activity logs have been cleared.');
    }

    private function ensureSuperAdmin(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }
}
