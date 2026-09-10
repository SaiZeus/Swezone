<?php

namespace App\Middleware; // or App\Http\Middleware depending on your Laravel version

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictEventAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user();

        if ($admin && $admin->isEventAdmin()) {
            // Ensure they have an assigned event, otherwise abort or log out
            if (!$admin->event_id) {
                abort(403, 'No event has been assigned to this account.');
            }

            $assignedSlug = $admin->event->slug;

            // Define allowed route names or paths for event admin
            // They can view attendees, export excel, and download tickets for their event
            $allowedRoutes = [
                'admin.events.attendees',
                'admin.events.attendees.export',
                'attendees.download_ticket',
                'admin.logout',
            ];

            $currentRoute = $request->route()->getName();

            // If they hit the base /admin dashboard, redirect them straight to their attendees page
            if ($currentRoute === 'admin.dashboard' || $request->path() === 'admin') {
                return redirect()->route('admin.events.attendees', ['event' => $assignedSlug]);
            }

            // Check if current route is allowed
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('admin.events.attendees', ['event' => $assignedSlug])
                    ->with('error', 'Unauthorized action.');
            }

            // Intercept route parameters to ensure they can ONLY view their assigned event
            $routeEvent = $request->route('event');
            if ($routeEvent) {
                $slug = is_object($routeEvent) ? $routeEvent->slug : $routeEvent;
                if ($slug !== $assignedSlug) {
                    return redirect()->route('admin.events.attendees', ['event' => $assignedSlug])
                        ->with('error', 'You can only access your assigned event.');
                }
            }
        }

        return $next($request);
    }
}