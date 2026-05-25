{{--
    Custom Video Player Partial
    Usage: @include('partials.video-player', ['src' => $media->stream_url, 'type' => $media->mime_type])

    Video is served via VideoStreamController which sends proper Accept-Ranges /
    Content-Range headers — this is what makes browser seeking (click-to-seek,
    drag-to-seek) work correctly.
--}}
@once
<style>
@keyframes vp-spin { to { transform: rotate(360deg); } }
</style>
@endonce

@php $vpId = uniqid('vp'); @endphp

<div id="vp-{{ $vpId }}"
     style="position:relative;aspect-ratio:9/16;max-width:320px;margin:0 auto;background:#000;border-radius:12px;overflow:hidden;-webkit-user-select:none;user-select:none;box-shadow:0 4px 20px rgba(0,0,0,0.4);">

    {{-- Video — NO native controls; pointer-events:none so our UI owns all input --}}
    <video id="vp-vid-{{ $vpId }}"
           preload="metadata"
           playsinline
           style="display:block;width:100%;height:100%;object-fit:contain;pointer-events:none;">
        <source src="{{ $src }}" type="{{ $type ?? 'video/mp4' }}">
    </video>

    {{-- Buffering spinner --}}
    <div id="vp-spinner-{{ $vpId }}"
         style="display:none;position:absolute;inset:0;z-index:10;align-items:center;justify-content:center;pointer-events:none;">
        <div style="width:44px;height:44px;border:3px solid rgba(255,255,255,0.15);border-top-color:#fff;border-radius:50%;animation:vp-spin 0.8s linear infinite;"></div>
    </div>

    {{-- Bottom gradient — non-interactive --}}
    <div style="position:absolute;bottom:0;left:0;right:0;height:48%;background:linear-gradient(to top,rgba(0,0,0,0.88) 0%,rgba(0,0,0,0.45) 50%,transparent 100%);pointer-events:none;z-index:1;"></div>

    {{--
        CENTER CLICK ZONE — play/pause.
        bottom:72px is CRITICAL — this div must NOT overlap the controls bar.
        Stopping here means clicks on the seek bar go straight to that element
        with zero z-index competition.
    --}}
    <div id="vp-center-{{ $vpId }}"
         style="position:absolute;top:0;left:0;right:0;bottom:72px;z-index:3;display:flex;align-items:center;justify-content:center;transition:opacity 0.3s;cursor:pointer;">
        <div style="width:66px;height:66px;background:rgba(0,0,0,0.45);border:2px solid rgba(255,255,255,0.28);border-radius:50%;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);">
            <svg id="vp-ci-play-{{ $vpId }}" width="28" height="28" viewBox="0 0 24 24" fill="#fff" style="margin-left:4px;">
                <path d="M8 5v14l11-7z"/>
            </svg>
            <svg id="vp-ci-pause-{{ $vpId }}" width="28" height="28" viewBox="0 0 24 24" fill="#fff" style="display:none;">
                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
            </svg>
        </div>
    </div>

    {{-- CONTROLS BAR — 72px tall, z-index 5 (always above center overlay) --}}
    <div id="vp-ctrl-{{ $vpId }}"
         style="position:absolute;bottom:0;left:0;right:0;height:72px;z-index:5;padding:0 12px 10px;display:flex;flex-direction:column;justify-content:flex-end;transition:opacity 0.3s;">

        {{-- Seek bar --}}
        <div id="vp-seek-{{ $vpId }}"
             style="position:relative;height:20px;cursor:pointer;touch-action:none;margin-bottom:6px;">
            {{-- Track --}}
            <div style="position:absolute;left:0;right:0;top:50%;transform:translateY(-50%);height:3px;background:rgba(255,255,255,0.2);border-radius:3px;"></div>
            {{-- Buffered --}}
            <div id="vp-buf-{{ $vpId }}" style="position:absolute;left:0;top:50%;transform:translateY(-50%);height:3px;background:rgba(255,255,255,0.38);border-radius:3px;width:0%;pointer-events:none;"></div>
            {{-- Played --}}
            <div id="vp-fill-{{ $vpId }}" style="position:absolute;left:0;top:50%;transform:translateY(-50%);height:3px;background:#CD571B;border-radius:3px;width:0%;pointer-events:none;"></div>
            {{-- Thumb --}}
            <div id="vp-thumb-{{ $vpId }}" style="position:absolute;top:50%;left:0%;transform:translate(-50%,-50%) scale(1);width:13px;height:13px;background:#fff;border-radius:50%;box-shadow:0 1px 5px rgba(0,0,0,0.6);pointer-events:none;transition:transform 0.1s;will-change:left;"></div>
        </div>

        {{-- Buttons row --}}
        <div style="display:flex;align-items:center;gap:4px;">

            <button id="vp-playbtn-{{ $vpId }}" title="Play/Pause"
                    style="background:none;border:none;cursor:pointer;padding:4px;line-height:0;flex-shrink:0;">
                <svg id="vp-b-play-{{ $vpId }}"  width="22" height="22" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg>
                <svg id="vp-b-pause-{{ $vpId }}" width="22" height="22" viewBox="0 0 24 24" fill="#fff" style="display:none;"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
            </button>

            <span id="vp-time-{{ $vpId }}"
                  style="color:rgba(255,255,255,0.88);font-size:11px;font-family:ui-monospace,SFMono-Regular,Menlo,monospace;white-space:nowrap;letter-spacing:0.03em;flex-shrink:0;">
                0:00 / 0:00
            </span>

            <div style="flex:1;"></div>

            {{-- Mute toggle --}}
            <button id="vp-mute-{{ $vpId }}" title="Mute/Unmute"
                    style="background:none;border:none;cursor:pointer;padding:4px;line-height:0;flex-shrink:0;">
                <svg id="vp-snd-{{ $vpId }}"   width="20" height="20" viewBox="0 0 24 24" fill="rgba(255,255,255,0.85)"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
                <svg id="vp-muted-{{ $vpId }}" width="20" height="20" viewBox="0 0 24 24" fill="rgba(255,255,255,0.85)" style="display:none;"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
            </button>

            {{-- Volume slider --}}
            <div id="vp-vol-{{ $vpId }}"
                 style="position:relative;width:58px;height:16px;cursor:pointer;touch-action:none;flex-shrink:0;">
                {{-- Track --}}
                <div style="position:absolute;left:0;right:0;top:50%;transform:translateY(-50%);height:3px;background:rgba(255,255,255,0.2);border-radius:3px;"></div>
                {{-- Fill --}}
                <div id="vp-volfill-{{ $vpId }}"
                     style="position:absolute;left:0;top:50%;transform:translateY(-50%);height:3px;background:#CD571B;border-radius:3px;width:100%;pointer-events:none;"></div>
                {{-- Thumb --}}
                <div id="vp-volthumb-{{ $vpId }}"
                     style="position:absolute;top:50%;left:100%;transform:translate(-50%,-50%);width:11px;height:11px;background:#fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.55);pointer-events:none;transition:transform 0.1s;"></div>
            </div>

            <button id="vp-fs-{{ $vpId }}" title="Fullscreen"
                    style="background:none;border:none;cursor:pointer;padding:4px;line-height:0;flex-shrink:0;">
                <svg id="vp-fs-in-{{ $vpId }}"  width="20" height="20" viewBox="0 0 24 24" fill="rgba(255,255,255,0.85)"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                <svg id="vp-fs-out-{{ $vpId }}" width="20" height="20" viewBox="0 0 24 24" fill="rgba(255,255,255,0.85)" style="display:none;"><path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/></svg>
            </button>

        </div>
    </div>

</div>

<script>
(function () {
    var id       = '{{ $vpId }}';
    var shell    = document.getElementById('vp-' + id);
    var vid      = document.getElementById('vp-vid-' + id);
    var seekBar  = document.getElementById('vp-seek-' + id);
    var fillEl   = document.getElementById('vp-fill-' + id);
    var bufEl    = document.getElementById('vp-buf-' + id);
    var thumbEl  = document.getElementById('vp-thumb-' + id);
    var timeEl   = document.getElementById('vp-time-' + id);
    var ctrlEl   = document.getElementById('vp-ctrl-' + id);
    var centerEl = document.getElementById('vp-center-' + id);
    var spinner  = document.getElementById('vp-spinner-' + id);
    var playBtn  = document.getElementById('vp-playbtn-' + id);
    var bPlay    = document.getElementById('vp-b-play-' + id);
    var bPause   = document.getElementById('vp-b-pause-' + id);
    var ciPlay   = document.getElementById('vp-ci-play-' + id);
    var ciPause  = document.getElementById('vp-ci-pause-' + id);
    var muteBtn  = document.getElementById('vp-mute-' + id);
    var sndEl    = document.getElementById('vp-snd-' + id);
    var mutedEl  = document.getElementById('vp-muted-' + id);
    var volBar   = document.getElementById('vp-vol-' + id);
    var volFill  = document.getElementById('vp-volfill-' + id);
    var volThumb = document.getElementById('vp-volthumb-' + id);
    var fsBtn    = document.getElementById('vp-fs-' + id);
    var fsIn     = document.getElementById('vp-fs-in-' + id);
    var fsOut    = document.getElementById('vp-fs-out-' + id);

    var hideTimer  = null;
    var isSeeking  = false;
    var seekFrac   = 0;
    var isVolDrag  = false;

    /* ── Helpers ── */
    function fmt(s) {
        if (!isFinite(s) || isNaN(s) || s < 0) return '0:00';
        var m = Math.floor(s / 60), sc = Math.floor(s % 60);
        return m + ':' + (sc < 10 ? '0' : '') + sc;
    }

    /* ── Seek bar redraw ── */
    function redraw() {
        var dur  = vid.duration;
        var frac = isSeeking ? seekFrac : (dur ? vid.currentTime / dur : 0);
        var pct  = (Math.max(0, Math.min(1, frac)) * 100).toFixed(3);
        fillEl.style.width = pct + '%';
        thumbEl.style.left = pct + '%';
        if (!isSeeking && dur && vid.buffered.length) {
            var bp = (vid.buffered.end(vid.buffered.length - 1) / dur * 100).toFixed(3);
            bufEl.style.width = bp + '%';
        }
        var cur = isSeeking ? (seekFrac * (dur || 0)) : (vid.currentTime || 0);
        timeEl.textContent = fmt(cur) + ' / ' + fmt(dur);
    }

    /* ── Volume bar redraw ── */
    function redrawVol() {
        var v   = vid.muted ? 0 : vid.volume;
        var pct = (Math.max(0, Math.min(1, v)) * 100).toFixed(1);
        volFill.style.width  = pct + '%';
        volThumb.style.left  = pct + '%';
        /* sync mute icons */
        sndEl.style.display   = vid.muted ? 'none'  : 'block';
        mutedEl.style.display = vid.muted ? 'block' : 'none';
    }

    function getFrac(e) {
        var rect    = seekBar.getBoundingClientRect();
        var clientX = (e.touches && e.touches.length)              ? e.touches[0].clientX
                    : (e.changedTouches && e.changedTouches.length) ? e.changedTouches[0].clientX
                    : e.clientX;
        return Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
    }

    function getVolFrac(e) {
        var rect    = volBar.getBoundingClientRect();
        var clientX = (e.touches && e.touches.length)              ? e.touches[0].clientX
                    : (e.changedTouches && e.changedTouches.length) ? e.changedTouches[0].clientX
                    : e.clientX;
        return Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
    }

    function doSeek(e) {
        seekFrac = getFrac(e);
        if (vid.duration && isFinite(vid.duration)) {
            vid.currentTime = seekFrac * vid.duration;
        }
        redraw();
    }

    function doVol(e) {
        var frac  = getVolFrac(e);
        vid.volume = frac;
        vid.muted  = (frac === 0);
        redrawVol();
    }

    /* ── Seek: mouse ── */
    seekBar.addEventListener('mousedown', function (e) {
        e.preventDefault();
        e.stopPropagation();
        isSeeking = true;
        thumbEl.style.transform = 'translate(-50%,-50%) scale(1.5)';
        doSeek(e);
    });
    document.addEventListener('mousemove', function (e) {
        if (isSeeking)  doSeek(e);
        if (isVolDrag)  doVol(e);
    });
    document.addEventListener('mouseup', function () {
        if (isSeeking) {
            isSeeking = false;
            thumbEl.style.transform = 'translate(-50%,-50%) scale(1)';
            redraw();
        }
        if (isVolDrag) {
            isVolDrag = false;
            volThumb.style.transform = 'translate(-50%,-50%) scale(1)';
        }
    });

    /* ── Seek: touch ── */
    seekBar.addEventListener('touchstart', function (e) {
        e.preventDefault();
        e.stopPropagation();
        isSeeking = true;
        thumbEl.style.transform = 'translate(-50%,-50%) scale(1.5)';
        doSeek(e);
    }, { passive: false });
    document.addEventListener('touchmove', function (e) {
        if (isSeeking) { e.preventDefault(); doSeek(e); }
        if (isVolDrag) { e.preventDefault(); doVol(e);  }
    }, { passive: false });
    document.addEventListener('touchend', function () {
        if (isSeeking) {
            isSeeking = false;
            thumbEl.style.transform = 'translate(-50%,-50%) scale(1)';
            redraw();
        }
        if (isVolDrag) {
            isVolDrag = false;
            volThumb.style.transform = 'translate(-50%,-50%) scale(1)';
        }
    });

    /* ── Seek bar hover ── */
    seekBar.addEventListener('mouseenter', function () {
        if (!isSeeking) thumbEl.style.transform = 'translate(-50%,-50%) scale(1.3)';
    });
    seekBar.addEventListener('mouseleave', function () {
        if (!isSeeking) thumbEl.style.transform = 'translate(-50%,-50%) scale(1)';
    });

    /* ── Volume: mouse ── */
    volBar.addEventListener('mousedown', function (e) {
        e.preventDefault();
        e.stopPropagation();
        isVolDrag = true;
        volThumb.style.transform = 'translate(-50%,-50%) scale(1.5)';
        doVol(e);
    });

    /* ── Volume: touch ── */
    volBar.addEventListener('touchstart', function (e) {
        e.preventDefault();
        e.stopPropagation();
        isVolDrag = true;
        volThumb.style.transform = 'translate(-50%,-50%) scale(1.5)';
        doVol(e);
    }, { passive: false });

    /* ── Volume bar hover ── */
    volBar.addEventListener('mouseenter', function () {
        if (!isVolDrag) volThumb.style.transform = 'translate(-50%,-50%) scale(1.3)';
    });
    volBar.addEventListener('mouseleave', function () {
        if (!isVolDrag) volThumb.style.transform = 'translate(-50%,-50%) scale(1)';
    });

    /* ── Play / Pause ── */
    function togglePlay() {
        if (vid.paused || vid.ended) vid.play();
        else vid.pause();
    }
    function syncIcons() {
        var p = vid.paused || vid.ended;
        ciPlay.style.display  = p ? 'block' : 'none';
        ciPause.style.display = p ? 'none'  : 'block';
        bPlay.style.display   = p ? 'block' : 'none';
        bPause.style.display  = p ? 'none'  : 'block';
    }
    vid.addEventListener('play',  syncIcons);
    vid.addEventListener('pause', syncIcons);
    vid.addEventListener('ended', syncIcons);
    centerEl.addEventListener('click', function (e) { e.stopPropagation(); togglePlay(); });
    playBtn.addEventListener('click',  function (e) { e.stopPropagation(); togglePlay(); });

    /* ── Progress updates (guarded during seek so timeupdate can't snap back) ── */
    vid.addEventListener('timeupdate',     function () { if (!isSeeking) redraw(); });
    vid.addEventListener('progress',       function () { if (!isSeeking) redraw(); });
    vid.addEventListener('loadedmetadata', redraw);
    vid.addEventListener('durationchange', redraw);

    /* ── Volume change (e.g. system-level) ── */
    vid.addEventListener('volumechange', redrawVol);

    /* ── Spinner ── */
    vid.addEventListener('waiting', function () { spinner.style.display = 'flex'; });
    vid.addEventListener('playing', function () { spinner.style.display = 'none'; });
    vid.addEventListener('canplay', function () { spinner.style.display = 'none'; });

    /* ── Mute toggle ── */
    muteBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        vid.muted = !vid.muted;
        redrawVol();
    });

    /* ── Fullscreen ── */
    fsBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (!document.fullscreenElement && !document.webkitFullscreenElement) {
            (shell.requestFullscreen || shell.webkitRequestFullscreen || function(){}).call(shell);
        } else {
            (document.exitFullscreen || document.webkitExitFullscreen || function(){}).call(document);
        }
    });
    function onFsChange() {
        var inFs = !!(document.fullscreenElement || document.webkitFullscreenElement);
        fsIn.style.display  = inFs ? 'none'  : 'block';
        fsOut.style.display = inFs ? 'block' : 'none';
    }
    document.addEventListener('fullscreenchange',       onFsChange);
    document.addEventListener('webkitfullscreenchange', onFsChange);

    /* ── Auto-hide controls when playing ── */
    function showControls() {
        ctrlEl.style.opacity   = '1';
        centerEl.style.opacity = '1';
        clearTimeout(hideTimer);
        if (!vid.paused && !vid.ended) {
            hideTimer = setTimeout(function () {
                ctrlEl.style.opacity   = '0';
                centerEl.style.opacity = '0';
            }, 3000);
        }
    }
    shell.addEventListener('mousemove',  showControls);
    shell.addEventListener('touchstart', showControls, { passive: true });
    vid.addEventListener('play',  showControls);
    vid.addEventListener('pause', function () {
        clearTimeout(hideTimer);
        ctrlEl.style.opacity   = '1';
        centerEl.style.opacity = '1';
    });

    syncIcons();
    redraw();
    redrawVol();
})();
</script>
