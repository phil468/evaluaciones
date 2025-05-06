<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
 
class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $nonce = bin2hex(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);
        \View::share('nonce', $nonce);
 
        $response = $next($request);
 
        // Headers básicos de seguridad
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Strict Transport Security con configuración robusta
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        
        // Políticas de referrer más estrictas
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy más permisiva para la aplicación
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        
        // Políticas de origen cruzado
        // $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        // $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        // $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');
        
        // Content Security Policy ajustada para la aplicación
        $cspDirectives = [
            "default-src 'self'",
            // Permitir scripts necesarios
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://oss.sheetjs.com https://cdnjs.cloudflare.com https://unpkg.com",
            // Permitir estilos necesarios
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com",
            // Permitir fuentes
            "font-src 'self' https://fonts.gstatic.com data:",
            // Permitir imágenes
            "img-src 'self' data: https: blob:",
            // Permitir conexiones
            "connect-src 'self'",
            // Otros directives necesarios
            "frame-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            // Actualización a HTTPS
            "upgrade-insecure-requests"
        ];
        
        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));
        
        // Control de acceso CORS más restrictivo
        if ($request->isMethod('OPTIONS')) {
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
            $response->headers->set('Access-Control-Max-Age', '3600');
        }

        // Permitir el origen de la aplicación
        $response->headers->set('Access-Control-Allow-Origin', $request->header('Origin', '*'));
        if ($request->header('Origin')) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}