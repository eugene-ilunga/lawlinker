<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventXSSAttackMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getMethod() == 'POST' || $request->getMethod() == 'PUT' || $request->getMethod() == 'PATCH') {
            $input = array_filter($request->except('_token'));
            $skipSanitizeKeys = [
                'freshpay_merchant_id',
                'freshpay_merchant_secret',
                'freshpay_callback_signature_secret',
                'freshpay_callback_aes_key',
                'freshpay_callback_aes_iv',
            ];

            $html_event_attributes = ['onload', 'onunload', 'onclick', 'ondblclick', 'onmousedown', 'onmouseup', 'onmouseover', 'onmousemove', 'onmouseout', 'onkeypress', 'onkeydown', 'onkeyup', 'onfocus', 'onblur', 'onsubmit', 'onreset', 'onchange', 'onselect', 'oninput', 'oncontextmenu'];

            array_walk_recursive($input, function (&$input, $key) use ($html_event_attributes, $skipSanitizeKeys) {
                if (in_array((string) $key, $skipSanitizeKeys, true)) {
                    return;
                }

                $input = strip_tags(str_replace(['&lt;', '&gt;'], '', $input), '<span><p><a><b><i><u><strong><br><hr><table><tr><th><td><ul><ol><li><h1><h2><h3><h4><h5><h6><del><ins><sup><sub><pre><address><img><figure><embed><iframe><video><style>');

                foreach ($html_event_attributes as $attribute) {
                    $input = str_replace($attribute, '', $input);
                }
            });

            $request->merge($input);
        }

        return $next($request);
    }
}
