<?php

namespace App\Http\Controllers;

use App\Models\PostMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoStreamController extends Controller
{
    /**
     * Stream a video file with full HTTP Range-request support.
     *
     * Why this exists:
     *   php artisan serve (PHP built-in server) does not send Accept-Ranges or
     *   Content-Range headers for files served through a storage symlink.
     *   Without those headers the browser refuses to seek and silently resets
     *   currentTime to 0.  This controller reads the file directly from disk and
     *   handles the Range header itself so seeking works in all environments.
     */
    public function stream(Request $request, PostMedia $media)
    {
        if (! $media->isVideo()) {
            abort(404);
        }

        $path = Storage::disk('public')->path($media->file_path);

        if (! file_exists($path)) {
            abort(404);
        }

        $mime     = $media->mime_type ?: 'video/mp4';
        $fileSize = filesize($path);
        $start    = 0;
        $end      = $fileSize - 1;
        $status   = 200;

        $headers = [
            'Content-Type'  => $mime,
            'Accept-Ranges' => 'bytes',
            'Content-Length'=> $fileSize,
            'Cache-Control' => 'public, max-age=3600',
        ];

        // ── Handle Range header ───────────────────────────────────────────
        if ($request->hasHeader('Range')) {
            preg_match('/bytes=(\d*)-(\d*)/', $request->header('Range'), $m);

            $start = (isset($m[1]) && $m[1] !== '') ? (int) $m[1] : 0;
            $end   = (isset($m[2]) && $m[2] !== '') ? (int) $m[2] : $fileSize - 1;
            $end   = min($end, $fileSize - 1);

            if ($start > $end || $start >= $fileSize) {
                return response('Range Not Satisfiable', 416, [
                    'Content-Range' => "bytes */{$fileSize}",
                ]);
            }

            $length = $end - $start + 1;

            $headers['Content-Length'] = $length;
            $headers['Content-Range']  = "bytes {$start}-{$end}/{$fileSize}";
            $status = 206; // Partial Content
        }

        $length = $end - $start + 1; // ensure set for non-Range requests too

        // ── Stream the bytes ──────────────────────────────────────────────
        return response()->stream(function () use ($path, $start, $length) {
            $fp = fopen($path, 'rb');
            fseek($fp, $start);

            $remaining = $length;
            while (! feof($fp) && $remaining > 0) {
                $chunk = min(65536, $remaining); // 64 KB chunks
                $data  = fread($fp, $chunk);
                if ($data === false) {
                    break;
                }
                echo $data;
                $remaining -= strlen($data);
                ob_flush();
                flush();
            }

            fclose($fp);
        }, $status, $headers);
    }
}
