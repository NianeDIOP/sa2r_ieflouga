<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRapportModifiable
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rapport = $request->route('rapport');
        
        if ($rapport && !$rapport->estModifiable()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce rapport ne peut plus être modifié car il a été soumis. Statut actuel : ' . $rapport->statut
                ], 403);
            }
            
            return redirect()
                ->route('etablissement.rapport-rentree.historique.index')
                ->with('error', 'Ce rapport ne peut plus être modifié.');
        }

        return $next($request);
    }
}
