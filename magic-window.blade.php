{{--

    Version 1.1.2
    Magic Window – draggable, pinnable persistable Livewire/Alpine window component.
    Made as a monolithic single file Component to allow quick an painless integration.
    resources/views/component/magic-window.blade.php

    Author:   Dr. Stefan Radolf & the Praxeln team
    Website:  https://github.com/schoolshark/magic-windows
    License:  MIT

    Praxeln is a central online platform for distributed management of
    clinical placements in nursing education. It connects students,
    training institutions, and practical training sites and supports
    placement planning, scheduling, messaging, and more.

    During the development of the Praxeln platform, many reusable
    Livewire components were created. Some of them – including this
    Magic Window – are released here under the MIT License in the
    hope that they will be useful to others.
--}}

@props([

    /* Important: set a unique windowId */
    'windowId' => null,
    'title' => '',

    /* Initial visibility: open | closed */
    'initialOpen' => false,

    /* Initial appearance mode */
    'workingMode' => 'window', // 'window' | 'window-pinned' (overlay) | 'window-anchored'

    /* Titlebar visibility */
    'titlebarType' => 'visible', // '' | 'hidden'

    /* Automatically store/load last position and size */
    'persist' => false,

    /* Optionally persist the open/closed state as well */
    'persistOpenState' => false,

    /* Persist inline height (after vertical resize) across reloads? */
    'persistHeight' => false,

    /* Throttle time in ms before a new save */
    'saveThrottleMs' => 200,

    /* Optional Tabler icon component in the title bar, e.g. 'tabler-plane-departure' */
    'iconName' => null,

    /* Initial position */
    'initLeft' => '5%',
    'initTop' => '5%',

    /* Convenience */
    'snap' => true,
    'snapThreshold' => 16,

    /* Viewport/container margin in px */
    'viewportMargin' => 0,

    /* Z-index base for all Magic Windows */
    'magicWindowIndexCounterStart' => 2000,

    /* Horizontal clamp to prevent X from leaving the parent container */
    'clampX' => false,

    /* Optional reset button in the title bar (off by default) */
    'showResetButton' => false,

    /* Default size via Tailwind classes */
    'defaultWide' => 'w-max md:w-3/4',
    'defaultHeight' => 'h-fit',

    /* Only provide the Tailwind color basenames.
       Instead of 'bg-gray-500' supply 'gray'.
       The component builds proper contrast classes automatically.
       Using the same basecolor and accentcolor yields a subtle accent.
       Use the provided “honeypots” in magic-window-test.blade.php
       so your bundler (e.g. Vite) picks up all color classes.

       Example:
       'basecolor'   => 'zinc',
       'accentcolor' => 'amber',
    */
    'basecolor' => 'zinc',
    'accentcolor' => 'pink',

    /* Layout classes: placeholders 'basecolor' and 'accentcolor' are injected automatically */
    'layoutWindowBody' => 'bg-basecolor-50 dark:bg-basecolor-50',
    'layoutWindowTitleBar' => ' h-8 p-1 bg-basecolor-600/80 dark:bg-basecolor-600/80 text-basecolor-50 group-focus:bg-accentcolor-600/100 ',
    'layoutWindowTitleBarIcon' => ' w-6 h-6 text-basecolor-50',
    'layoutWindowTitleBarButton' => ' inline-flex items-center justify-center p-0.5 rounded-lg ',
    'layoutWindowTitleBarButtonIcon' => ' w-6 h-6 text-basecolor-50',
    'layoutWindowTitlebarButtonGroup' => ' flex flex-row items-center justify-center gap-x-1 ',
    'layoutWindowBackground' => 'bg-basecolor-50 dark:bg-basecolor-50 text-basecolor-950 dark:text-basecolor-950',
    'layoutWindowBorder' => ' rounded-lg border-2 border-basecolor-600/80 dark:border-basecolor-600/80 focus:border-accentcolor-600/100 dark:focus:border-accentcolor-600/100 focus:outline-none focus:ring-0',
    'layoutWindowShadow' => 'shadow shadow-basecolor-600/40',
    'layoutWindowShadowPinned' => 'shadow-md shadow-basecolor-600/40 ',

    /* Internal: icon path snippets for title bar buttons */
    'iconPrefixPre'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="',
    'iconPrefixPost' => '"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>',
    'iconSuffix'     => '</svg>',
    'iconPin'        => '<path d="M15 4.5l-4 4l-4 1.5l-1.5 1.5l7 7l1.5 -1.5l1.5 -4l4 -4" /><path d="M9 15l-4.5 4.5" /><path d="M14.5 4l5.5 5.5" />',
    'iconPinOff'     => '<path d="M9 4v6l-2 4v2h10v-2l-2 -4v-6" /><path d="M12 16l0 5" /><path d="M8 4l8 0" />',
    'iconAnchor'     => '<path d="M12 9v12m-8 -8a8 8 0 0 0 16 0m1 0h-2m-14 0h-2" /><path d="M12 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />',
    'iconAnchorOff'  => '<path d="M12 12v9" /><path d="M4 13a8 8 0 0 0 14.138 5.13m1.44 -2.56a7.99 7.99 0 0 0 .422 -2.57" /><path d="M21 13h-2" /><path d="M5 13h-2" /><path d="M12.866 8.873a3 3 0 1 0 -3.737 -3.747" /><path d="M3 3l18 18" />',
    'iconSquareX'    => '<path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" /><path d="M9 9l6 6m0 -6l-6 6" />',
    'iconAutofit'    => '<path d="M6 4l-3 3l3 3" /><path d="M18 4l3 3l-3 3" /><path d="M4 14m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M10 7h-7" /><path d="M21 7h-7" />',

    /* Message after global LocalStorage reset */
    'localStorageNotice'    => __('Window Settings cleaned'),
])

@php
    /* Inject placeholders 'basecolor' and 'accentcolor' into all layout classes */
    if (!function_exists('mw_inject_colors')) {
        function mw_inject_colors(array $props): array
        {
            $base   = $props['basecolor']   ?? 'gray';
            $accent = $props['accentcolor'] ?? 'pink';

            $map = [
                'basecolor'   => (string) $base,
                'accentcolor' => (string) $accent,
            ];

            $repl = function ($v) use (&$repl, $map) {
                if (is_string($v)) return strtr($v, $map);
                if (is_array($v)) {
                    foreach ($v as $k => $vv) $v[$k] = $repl($vv);
                }
                return $v;
            };

            foreach ($props as $k => $v) {
                if ($k === 'basecolor' || $k === 'accentcolor') continue;
                $props[$k] = $repl($v);
            }
            return $props;
        }
    }

    /* Collect props, inject colors, expose variables */
    $__mwProps = compact(
        'basecolor', 'accentcolor',
        'layoutWindowBody',
        'layoutWindowTitleBar',
        'layoutWindowTitleBarIcon',
        'layoutWindowTitleBarButton',
        'layoutWindowTitleBarButtonIcon',
        'layoutWindowTitlebarButtonGroup',
        'layoutWindowBackground',
        'layoutWindowBorder',
        'layoutWindowShadow',
        'layoutWindowShadowPinned'
    );
    $__mwProps = mw_inject_colors($__mwProps);
    extract($__mwProps, EXTR_OVERWRITE);
@endphp

@php
    if (!$windowId) {
        throw new InvalidArgumentException("magic-window: 'windowId' is required and must be unique.");
    }
    $safeId = \Illuminate\Support\Str::slug($windowId, '-');
    /* Wire key = single source of truth for Alpine/Livewire identity */
    $key = 'mw-'.$safeId;
    $fallbackStyle = "left: {$initLeft}; top: {$initTop};";
    /* Event suffix derived from windowId */
    $eventSuffix = $safeId;
@endphp

<div
    wire:key="{{ $key }}"
    wire:ignore.self
    x-data="{
        // ---------------------------
        // Config + persistence
        // ---------------------------
        snap: @js($snap),
        snapT: @js($snapThreshold),

        persist: @js($persist),
        persistOpenState: @js($persistOpenState),
        persistHeight: @js($persistHeight),
        storageKeyBase: 'praxeln.magic-window.{{ $key }}',
        saveThrottleMs: @js($saveThrottleMs),
        _t_lastSave: 0,
        _t_timer: null,

        mwTitleId: 'mw-title-{{ $key }}',
        margin: @js((int)$viewportMargin),
        clampX: @js($clampX),

        // Defaults for reboot
        initLeft: @js($initLeft),
        initTop: @js($initTop),
        defaultWorkingMode: @js($workingMode),
        initialOpenState: @js($initialOpen),

        // ---------------------------
        // Mode + visibility
        // ---------------------------
        open: @js($initialOpen),
        workingMode: @js($workingMode),

        // Titlebar visibility state
        titlebarVisible: @js($titlebarType !== 'hidden'),

        // ---------------------------
        // Coordinates
        // Window mode: container-based (cx, cy)
        // Pinned mode: viewport-based  (vx, vy)
        // ---------------------------
        cx: 0, cy: 0,
        vx: 0, vy: 0,

        // ---------------------------
        // Size
        // ---------------------------
        useInlineSize: false,
        w: null, h: null,
        hasVerticalResize: false,
        sizeClass: @js($defaultWide . ' ' . $defaultHeight),
        dockSizeClass: @js('w-full ' . $defaultHeight),

        // ---------------------------
        // Parent bounds
        // ---------------------------
        parentElement: null, parentRect: null,
        maxW: 0, maxH: 0,

        // ---------------------------
        // Interaction
        // ---------------------------
        dragging:false, resizing:false, rs:'', sx:0, sy:0, startW:0, startH:0,

        // ---------------------------
        // Z-order
        // ---------------------------
        z: 0,

        // ---------------------------
        // Init
        // ---------------------------
        init(){
            // Global listener: clear all Magic Window LocalStorage keys and alert once.
            if (!window.__magicWindowGlobalClearListener) {

                const handler = () => {
                    try {
                        const del = [];
                        for (let i = 0; i < localStorage.length; i++) {
                            const k = localStorage.key(i);
                            if (k && k.startsWith('praxeln.magic-window.')) del.push(k);
                        }
                        del.forEach(k => localStorage.removeItem(k));
                    } catch(_) {}
                    try { alert('{{$localStorageNotice}}'); } catch(_) {}
                };

                window.addEventListener('magic-window-local-storage-clear', handler, { passive: true });
                window.addEventListener('recke-reset-windows',              handler, { passive: true });
                window.__magicWindowGlobalClearListener = handler;
                window.__magicWindowGlobalRefCount = 0;
            }
            window.__magicWindowGlobalRefCount++;

            // Z-index bootstrap using the prop as base
            if (typeof window.magicWindowIndexCounter !== 'number') {
                window.magicWindowIndexCounter = Number(@js($magicWindowIndexCounterStart)) || 2000;
            }
            this.z = ++window.magicWindowIndexCounter;

            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();

            // Initial position
            this.cx = this._px(this.initLeft, 'x');
            this.cy = Math.max(0, this._px(this.initTop, 'y'));

            // Load state
            this._load();

            // If initially pinned, derive viewport coordinates from current rect
            if (this.isPinnedLike()) {
                this._ensureViewportCoordsFromCurrentRect();
            }

            // On mount: keep in bounds and focus if open
            this.$nextTick(() => {
                this._updateMax();
                this._applyMax();
                this._keepInBounds();
                if (this.open) { this.bringToFront(); this.focusFrame(); }
            });

            // Auto focus whenever the window opens
            this.$watch('open', (v) => {
                if (v) { this.bringToFront(); this._keepInBounds(); this.focusFrame(); }
                if (this.persist && this.persistOpenState) this._save();
            });
        },

        // ---------------------------
        // Mode helpers
        // ---------------------------
        modeIs(m){ return this.workingMode === m },
        isFree(){ return this.modeIs('window') },
        isPinnedLike(){ return this.modeIs('window-pinned') },
        isAnchored(){ return this.modeIs('window-anchored') },

        // Hard mode switch with proper coordinate conversion
        setMode(m){
            const prev = this.workingMode;
            if (prev === m) return;

            // window → pinned
            if (this.isFree() && m === 'window-pinned') {
                this._convertWindowToViewport();
                this.workingMode = m;
                this._updateMax(); this._applyMax(); this._keepInBounds(); this._save();
                return;
            }

            // pinned → window
            if (this.isPinnedLike() && m === 'window') {
                this._convertViewportToWindow();
                this.workingMode = 'window';
                this._updateMax(); this._applyMax(); this._keepInBounds(); this._save();
                return;
            }

            // 'anchored' only affects style/size, not coordinates
            if (m === 'window-anchored') {
                this.workingMode = m;
                this.useInlineSize = false; this.w = null; this.h = null;
                this._updateMax(); this._save();
                return;
            }

            this.workingMode = m;
            this._updateMax(); this._applyMax(); this._keepInBounds(); this._save();
        },

        togglePin(){
            if (this.isAnchored()) return;
            this.setMode(this.isFree() ? 'window-pinned' : 'window');
        },
        toggleDock(){
            if (this.isFree()) this.setMode('window-anchored');
            else if (this.isAnchored()) this.setMode('window');
        },

        // ---------------------------
        // Coordinate conversions
        // ---------------------------
        _convertWindowToViewport(){
          this._updateParentRect();
          const pr = this.parentRect || { left: 0, top: 0 };
          if (this.isPinnedLike()) {
            this.vx = Math.round(this.cx + pr.left);
          } else {
            const r = this.$refs.frame?.getBoundingClientRect();
            this.vx = r ? Math.round(r.left) : this.cx + pr.left;
          }
          this.vy = Math.max(0, Math.round(this.cy + pr.top));
        },
        _convertViewportToWindow(){
          this._updateParentRect();
          const pr = this.parentRect || { left: 0, top: 0 };
          this.cx = Math.round(this.vx - pr.left);
          this.cy = Math.max(0, Math.round(this.vy - pr.top));
        },
        _ensureViewportCoordsFromCurrentRect(){
            const r = this.$refs.frame?.getBoundingClientRect?.();
            if (!r) return;
            this.vx = Math.round(r.left);
            this.vy = Math.max(0, Math.round(r.top));
        },

        // ---------------------------
        // LocalStorage
        // ---------------------------
        storageKey(){ return this.storageKeyBase; },
        _loadRaw(){ if(!this.persist) return {}; try{ return JSON.parse(localStorage.getItem(this.storageKey())||'{}')||{} }catch(_){ return {} } },
        _load(){
            const s = this._loadRaw();

            // 'open' can optionally be restored, otherwise initialOpen controls start visibility.
            if (this.persist && this.persistOpenState && typeof s.open === 'boolean') {
                this.open = !!s.open;
            } else {
                this.open = this.initialOpenState;
            }

            if (s.meta && typeof s.meta === 'object' && this.persistHeight) {
                this.hasVerticalResize = !!s.meta.hasVerticalResize;
            } else {
                this.hasVerticalResize = false;
            }

            if (s.workingMode) this.workingMode = s.workingMode;

            // Coordinates for window mode
            if (s.free) {
                if (Number.isFinite(s.free.x)) this.cx = s.free.x;
                if (Number.isFinite(s.free.y)) this.cy = Math.max(0, s.free.y);
            }

            // Coordinates for pinned mode
            if (s.pinned) {
                if (Number.isFinite(s.pinned.vx)) this.vx = s.pinned.vx;
                if (Number.isFinite(s.pinned.vy)) this.vy = Math.max(0, s.pinned.vy);
            }

            // Size: width always, height only if there was a vertical resize and we persist it
            const src = this.isPinnedLike() ? s.pinned : s.free;
            this.useInlineSize = !!src?.useInlineSize;

            if (this.useInlineSize) {
                this.w = Number.isFinite(src?.w) ? src.w : null;
                this.h = (this.persistHeight && this.hasVerticalResize && Number.isFinite(src?.h)) ? src.h : null;
            } else {
                this.w = null;
                this.h = null;
            }
        },

        // Raw save without throttling
        _saveRaw(o){
            if (!this.persist) return;
            // legacy guard: external code can disable saving by setting this flag
            if (window.__magicWindowStorageKilled) return;
            try { localStorage.setItem(this.storageKey(), JSON.stringify(o)) } catch(_){}
        },

        __doSave(){
            if (!this.persist) return;
            const s = this._loadRaw();

            if (this.persistOpenState) {
                s.open = !!this.open;
            }

            s.workingMode = this.workingMode;

            if (this.persistHeight) {
                s.meta = s.meta || {};
                s.meta.hasVerticalResize = !!this.hasVerticalResize;
            } else if (s.meta && 'hasVerticalResize' in s.meta) {
                delete s.meta.hasVerticalResize;
            }

            const hToStore = (this.persistHeight && this.hasVerticalResize && this.useInlineSize) ? this.h : null;

            // Window mode state
            s.free = {
                x: Math.round(this.cx),
                y: Math.max(0, Math.round(this.cy)),
                w: this.useInlineSize ? this.w : null,
                h: hToStore,
                useInlineSize: !!this.useInlineSize
            };

            // Pinned mode state
            s.pinned = {
                vx: Math.round(this.vx),
                vy: Math.max(0, Math.round(this.vy)),
                w: this.useInlineSize ? this.w : null,
                h: hToStore,
                useInlineSize: !!this.useInlineSize
            };

            this._saveRaw(s);
        },

        // Throttled save
        _save(){
            const now  = Date.now();
            const wait = Number(this.saveThrottleMs) || 0;

            if (wait <= 0) { this.__doSave(); return; }

            const remaining = wait - (now - this._t_lastSave);

            if (remaining <= 0) {
                if (this._t_timer) { clearTimeout(this._t_timer); this._t_timer = null; }
                this._t_lastSave = now;
                this.__doSave();
            } else if (!this._t_timer) {
                this._t_timer = setTimeout(() => {
                    this._t_timer = null;
                    this._t_lastSave = Date.now();
                    this.__doSave();
                }, remaining);
            }
        },

        // Flush immediate save before critical actions (e.g., close)
        _flushSaveNow() {
            if (this._t_timer) { clearTimeout(this._t_timer); this._t_timer = null; }
            this._t_lastSave = Date.now();
            this.__doSave();
        },

        // ---------------------------
        // Bounds and max sizes
        // ---------------------------
        _updateParentRect(){
            if (!this.parentElement) this._syncOffsetParent();
            if (!this.parentElement) return;
            this.parentRect = this.parentElement.getBoundingClientRect();
        },

        _syncOffsetParent(){
            const off = this.$refs.frame?.offsetParent;
            if (off) this.parentElement = off;
            if (!this.parentElement) {
                let p = this.$el.parentElement;
                while (p) {
                    const cs = getComputedStyle(p);
                    if (cs.position !== 'static') { this.parentElement = p; break; }
                    p = p.parentElement;
                }
                if (!this.parentElement) this.parentElement = this.$el.parentElement;
            }
            if (this.parentElement && getComputedStyle(this.parentElement).position === 'static') {
                this.parentElement.style.position = 'relative';
            }
        },
        _updateMax(){
            if (this.isAnchored()) { this.maxW = Infinity; this.maxH = Infinity; return; }
            const baseW = this.isPinnedLike() ? window.innerWidth  : (this.parentRect?.width  || window.innerWidth);
            const baseH = this.isPinnedLike() ? window.innerHeight : (this.parentRect?.height || window.innerHeight);
            this.maxW = Math.max(200, baseW - 2*this.margin);
            this.maxH = Math.max(150, baseH - 2*this.margin);
        },
        _applyMax(){
            if (this.isAnchored()) {
                // Anchored windows are layout-controlled, no inline size
                this.useInlineSize = false;
                this.w = null;
                this.h = null;
                return;
            }

            if (!this.useInlineSize) return;

            // Width: always clamp when we are in inline-size mode
            if (this.w == null || !Number.isFinite(this.w)) {
                this.w = this.maxW;
            }
            this.w = Math.min(this.w, this.maxW);

            // Height:
            // - If there was a vertical resize, clamp and keep explicit height
            // - If not, keep h = null → no inline height → browser/content control
            if (this.hasVerticalResize) {
                if (this.h == null || !Number.isFinite(this.h)) {
                    this.h = this.maxH;
                }
                this.h = Math.min(this.h, this.maxH);
            } else {
                // No vertical resize → never force an inline height
                this.h = null;
            }
        },


        // Actual frame size used for clamping/snap calculations
        _getFrameSize(){
            const r = this.$refs.frame?.getBoundingClientRect?.();
            const baseW = this.isPinnedLike() ? window.innerWidth  : (this.parentRect?.width  || window.innerWidth);
            const baseH = this.isPinnedLike() ? window.innerHeight : (this.parentRect?.height || window.innerHeight);

            let w = this.useInlineSize && Number.isFinite(this.w)
                ? this.w
                : (r?.width ?? Math.round(baseW * 0.6));

            let h = this.useInlineSize && Number.isFinite(this.h)
                ? this.h
                : (r?.height ?? Math.round(baseH * 0.6));

            // Min/max constraints
            w = Math.min(Math.max(120, w), this.maxW);
            h = Math.min(Math.max(120, h), this.maxH);
            return { w, h };
        },

        _keepInBounds(){
            if (!this.open || this.isAnchored()) return;

            // Always use current parent bounds
            this._updateParentRect();
            this._updateMax();

            const { w, h } = this._getFrameSize();

            if (this.isPinnedLike()) {
                const vw = window.innerWidth;
                const vh = window.innerHeight;

                // Y is always clamped
                const minY = 0;
                const maxY = Math.max(this.margin, vh - this.margin - h);
                this.vy = Math.min(Math.max(this.vy, minY), maxY);

                // X optionally clamped
                if (this.clampX) {
                    const minX = this.margin;
                    const maxX = Math.max(this.margin, vw - this.margin - w);
                    this.vx = Math.min(Math.max(this.vx, minX), maxX);
                }
            } else {
                const W = this.parentRect?.width  || window.innerWidth;
                const H = this.parentRect?.height || window.innerHeight;

                // Y is always clamped
                const minY = 0;
                const maxY = Math.max(this.margin, H - this.margin - h);
                this.cy = Math.min(Math.max(this.cy, minY), maxY);

                // X optionally clamped
                if (this.clampX) {
                    const minX = this.margin;
                    const maxX = Math.max(this.margin, W - this.margin - w);
                    this.cx = Math.min(Math.max(this.cx, minX), maxX);
                }
            }
        },

        // -------------------------------------------
        // Drag/resize only in 'window' mode
        // ------------------------------------------
        bringToFront(){
            if (typeof window.magicWindowIndexCounter !== 'number') {
                window.magicWindowIndexCounter = Number(@js($magicWindowIndexCounterStart)) || 2000;
            }
            this.z = ++window.magicWindowIndexCounter;
        },
        isInteractive(t){ return !!t.closest('button, a, input, select, textarea, [contenteditable=true], .no-drag') },
        startDrag(e){
            if (!this.isFree() || this.isInteractive(e.target)) return;
            this.dragging=true;
            this.sx=e.clientX - this.cx; this.sy=e.clientY - this.cy;
            this.bringToFront();
            this._keepInBounds();
        },
        startResize(e,dir){
            if (!this.isFree()) return;
            this.resizing = true;
            this.rs = dir;
            this.sx = e.clientX;
            this.sy = e.clientY;

            const affectsX = dir.includes('e') || dir.includes('w');
            const affectsY = dir.includes('n') || dir.includes('s');

            if (affectsY) this.hasVerticalResize = true;

            const r = this.$refs.frame.getBoundingClientRect();

            /* freeze width before doing anything / even while resizing the wide,
             = preventing fallback to tailwind-classes */

            if (!this.useInlineSize) {
                this.w = Math.round(r.width);                 // Breite immer setzen
                if (affectsY) this.h = Math.round(r.height);  // Höhe nur bei vertikalem Resize setzen
                this.useInlineSize = true;
            } else {
                if (this.w == null) this.w = Math.round(r.width);
                if (this.h == null && affectsY) this.h = Math.round(r.height);
            }

            this.startW = this.w ?? Math.round(r.width);
            this.startH = this.h ?? Math.round(r.height);

            this.bringToFront();
            this._keepInBounds();
        },
        onPointerMove(e){
            if (!this.isFree()) return;
            if (this.dragging){
                this.cx = e.clientX - this.sx;
                this.cy = Math.max(0, e.clientY - this.sy);
            } else if (this.resizing){
                const dx = e.clientX - this.sx, dy = e.clientY - this.sy;
                if (this.rs.includes('e')) this.w = Math.max(120, Math.min(this.startW + dx, this.maxW));
                if (this.rs.includes('s')) this.h = Math.max(120, Math.min(this.startH + dy, this.maxH));
                if (this.rs.includes('w')) {
                    const nw = Math.max(120, Math.min(this.startW - dx, this.maxW));
                    const moved = nw - this.w;
                    this.w = nw; this.cx -= moved;
                }
                if (this.rs.includes('n')) {
                    const nh = Math.max(120, Math.min(this.startH - dy, this.maxH));
                    const moved = nh - this.h;
                    this.h = nh; this.cy = Math.max(0, this.cy - moved);
                }
            }
            this._updateParentRect();
            this._updateMax();
            this._applyMax();
            this._keepInBounds();
        },
        endAction(){
            if (!this.dragging && !this.resizing) return;
            this.dragging=false; this.resizing=false; this.rs='';
            this._applyMax();
            this._keepInBounds();
            if (this.snap) this._snapNow();
            this._flushSaveNow(); // final state save; immediate if enabled
        },

        // ---------------------------
        // Snap to edges if close
        // ---------------------------
        _snapNow(){
            if (!this.open || this.isAnchored() || !this.snap) return;

            const { w, h } = this._getFrameSize();

            if (this.isPinnedLike()){
                const vw = window.innerWidth;
                const vh = window.innerHeight;
                const L = this.margin, T = 0;
                const R = (vw - this.margin) - w;
                const B = (vh - this.margin) - h;

                if (Math.abs(this.vx - L) <= this.snapT) this.vx = L;
                if (Math.abs(this.vy - T) <= this.snapT) this.vy = T;
                if (Math.abs(R - this.vx) <= this.snapT) this.vx = R;
                if (Math.abs(B - this.vy) <= this.snapT) this.vy = B;
                return;
            }

            const W = this.parentRect?.width  || window.innerWidth;
            const H = this.parentRect?.height || window.innerHeight;
            const L = this.margin, T = 0;
            const R = (W - this.margin) - w;
            const B = (H - this.margin) - h;

            if (Math.abs(this.cx - L) <= this.snapT) this.cx = L;
            if (Math.abs(this.cy - T) <= this.snapT) this.cy = T;
            if (Math.abs(R - this.cx) <= this.snapT) this.cx = R;
            if (Math.abs(B - this.cy) <= this.snapT) this.cy = B;
        },

        // ---------------------------
        // Alignment helpers
        // ---------------------------
        alignLeft(){
            if (!this.open) return;
            if (!this.isFree()) this.setMode('window');
            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();

            const minX = this.clampX ? this.margin : 0;
            this.cx = minX;

            this._keepInBounds();
            this.bringToFront();
            this._save();
        },
        alignRight(){
            if (!this.open) return;
            if (!this.isFree()) this.setMode('window');
            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();
            const { w } = this._getFrameSize();

            const W = this.parentRect?.width || window.innerWidth;
            const maxX = this.clampX
                ? Math.max(this.margin, W - this.margin - w)
                : (W - w);
            this.cx = maxX;

            this._keepInBounds();
            this.bringToFront();
            this._save();
        },
        alignXCenter(){
            if (!this.open) return;
            if (!this.isFree()) this.setMode('window');
            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();
            const { w } = this._getFrameSize();

            const W = this.parentRect?.width || window.innerWidth;
            let x = (W - w) / 2;
            if (this.clampX) {
                const minX = this.margin;
                const maxX = Math.max(this.margin, W - this.margin - w);
                x = Math.min(Math.max(x, minX), maxX);
            }
            this.cx = x;

            this._keepInBounds();
            this.bringToFront();
            this._save();
        },
        alignTop(){
            if (!this.open) return;
            if (!this.isFree()) this.setMode('window');
            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();

            this.cy = 0;

            this._keepInBounds();
            this.bringToFront();
            this._save();
        },
        alignBottom(){
            if (!this.open) return;
            if (!this.isFree()) this.setMode('window');
            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();
            const { h } = this._getFrameSize();

            // Window-Mode: an unteren Rand des sichtbaren Bereichs ausrichten
            const pr = this.parentRect;
            const parentTop    = pr?.top ?? 0;                     // relativ zum Viewport
            const parentHeight = pr?.height ?? window.innerHeight;
            const parentBottom = parentTop + parentHeight;
            const viewportBottom = window.innerHeight;

            // Untere Grenze = min(Parent-Bottom, Viewport-Bottom)
            const targetBottom = Math.min(parentBottom, viewportBottom);

            // in lokale Koordinaten des Parents umrechnen
            const localTop = targetBottom - h - parentTop;
            this.cy = localTop;

            this._keepInBounds();
            this.bringToFront();
            this._save();
        },
        alignYCenter(){
            if (!this.open) return;
            if (!this.isFree()) this.setMode('window');
            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();
            const { h } = this._getFrameSize();

            const H = this.parentRect?.height || window.innerHeight;
            const minY = 0;
            const maxY = Math.max(this.margin, H - this.margin - h);
            let y = (H - h) / 2;
            y = Math.min(Math.max(y, minY), maxY);
            this.cy = y;

            this._keepInBounds();
            this.bringToFront();
            this._save();
        },
        setWidthPercent(width){
            if (!this.open) return;
            if (!this.isFree()) this.setMode('window');

            const pct = Number(width);
            if (!Number.isFinite(pct) || pct <= 0) return;

            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();

            const baseW = this.parentRect?.width || window.innerWidth;

            let newW = Math.round(baseW * Math.min(pct, 100) / 100);

            // minimale/maximale Breite respektieren
            newW = Math.min(Math.max(120, newW), this.maxW);

            this.useInlineSize = true;
            this.w = newW;

            this._applyMax();
            this._keepInBounds();
            this.bringToFront();
            this._save();
        },

        // ---------------------------
        // Reset / Reboot
        // ---------------------------
        resetToDefaultWindow(){
            // Drop all persisted state for this window
            try { localStorage.removeItem(this.storageKey()); } catch(_) {}

            this.workingMode = 'window';
            this.useInlineSize = false;
            this.w = null;
            this.h = null;
            this.hasVerticalResize = false;

            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();

            this.cx = this._px(this.initLeft, 'x');
            this.cy = Math.max(0, this._px(this.initTop, 'y'));

            this._applyMax();
            this._keepInBounds();
            this.bringToFront();
            this.focusFrame();
            this._save();
        },

        reboot(){
            // optional: re-enable saving if some previous code disabled it
            if (window.__magicWindowStorageKilled) {
                window.__magicWindowStorageKilled = false;
            }

            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();

            // Baseline defaults
            this.workingMode = this.defaultWorkingMode;
            this.open        = this.initialOpenState;
            this.useInlineSize = false;
            this.w = null;
            this.h = null;
            this.hasVerticalResize = false;

            this.cx = this._px(this.initLeft, 'x');
            this.cy = Math.max(0, this._px(this.initTop, 'y'));
            this.vx = 0;
            this.vy = 0;

            // Reload persisted state – falls vorhanden
            this._load();

            if (this.isPinnedLike()) {
                this._ensureViewportCoordsFromCurrentRect();
            }

            this.$nextTick(() => {
                this._updateMax();
                this._applyMax();
                this._keepInBounds();
                if (this.open) {
                    this.bringToFront();
                    this.focusFrame();
                }
            });
        },

        // ---------------------------
        // Utils
        // ---------------------------
        _px(v, axis){
            if (typeof v === 'number') return v;
            const baseW = this.parentRect?.width  || window.innerWidth;
            const baseH = this.parentRect?.height || window.innerHeight;
            if (typeof v !== 'string') return axis==='x' ? Math.round(baseW*0.1) : Math.round(baseH*0.1);
            v = v.trim();
            if (v.endsWith('px')) return parseFloat(v);
            if (v.endsWith('%'))  return Math.round((axis==='x'?baseW:baseH) * parseFloat(v)/100);
            if (v.endsWith('vw')) return Math.round(window.innerWidth * parseFloat(v)/100);
            if (v.endsWith('vh')) return Math.round(window.innerHeight * parseFloat(v)/100);
            if (/^\d+$/.test(v)) return parseFloat(v);
            return axis==='x' ? Math.round(baseW*0.1) : Math.round(baseH*0.1);
        },
        focusFrame(){
            this.$nextTick(() => {
                if (this.$refs.frame) {
                    try { this.$refs.frame.focus({ preventScroll: true }) } catch(_) { this.$refs.frame.focus() }
                }
            });
        },

        // ---------------------------
        // Styles/classes
        // ---------------------------
        frameClass(){
            const base = this.isAnchored() ? this.dockSizeClass : (this.useInlineSize ? '' : this.sizeClass);
            const borderRound = this.isAnchored()
                ? ''
                : '{{ $layoutWindowBorder }}';
            const pinnedExtra = this.isPinnedLike() ? @js($layoutWindowShadowPinned) : '';
            return base + ' min-w-0 min-h-0 ' + pinnedExtra + ' ' + borderRound;
        },
        frameStyle(){
            const disp = this.open ? 'display:flex;' : 'display:none;';
            if (this.isAnchored()) return `${disp}position:static;`;

            const sizePart = () => {
                let s = '';
                if (this.useInlineSize && this.w) s += ` width:${this.w}px;`;
                if (this.useInlineSize && this.h) s += ` height:${this.h}px;`;
                return s;
            };

            let s = `${disp}max-width:${this.maxW}px; max-height:${this.maxH}px; z-index:${this.z};`;

            s += this.isPinnedLike()
                ? 'position:fixed;'
                : 'position:absolute;';

            s += ` left:${Math.round(this.isPinnedLike() ? this.vx : this.cx)}px; top:${Math.round(this.isPinnedLike() ? this.vy : this.cy)}px;`;
            s += sizePart();
            return s;
        },

        // ---------------------------
        // Resize/scroll listeners
        // ---------------------------
        onResize(){
            if (!this.open) return;
            this._syncOffsetParent();
            this._updateParentRect();
            this._updateMax();
            this._applyMax();
            this._keepInBounds();
            this._save();
        },
        onScroll(){
            this._keepInBounds();
        },
    }"
    x-init="
        init();

        // Define flush handler
        const __mwFlush = $data._flushSaveNow.bind($data);

        // Runtime listeners
        window.addEventListener('beforeunload', __mwFlush, { passive: true });
        window.addEventListener('pagehide',     __mwFlush, { passive: true });

        // Cleanup (Alpine >= 3)
        if (typeof $cleanup === 'function') {
            $cleanup(() => {
                window.removeEventListener('beforeunload', __mwFlush);
                window.removeEventListener('pagehide',     __mwFlush, { passive: true });

                // Persist final state on destroy
                __mwFlush();

                // Optionally drop the global reset listener if no window remains
                if (window.__magicWindowGlobalClearListener) {
                    window.__magicWindowGlobalRefCount = (window.__magicWindowGlobalRefCount || 1) - 1;
                    if (window.__magicWindowGlobalRefCount <= 0) {
                        window.removeEventListener('magic-window-local-storage-clear', window.__magicWindowGlobalClearListener);
                        window.removeEventListener('recke-reset-windows',              window.__magicWindowGlobalClearListener);
                        delete window.__magicWindowGlobalClearListener;
                        delete window.__magicWindowGlobalRefCount;
                    }
                }
            });
        }
    "

    @pointermove.window="onPointerMove($event)"
    @pointerup.window="endAction()"
    @pointercancel.window="endAction()"
    @keydown.escape.window="if(open){ endAction(); open=false; _flushSaveNow() }"
    @resize.window="onResize()"
    @orientationchange.window="onResize()"
    @scroll.window.passive="onScroll()"

    {{-- Window visibility events --}}
    @magic-window-open-{{ $eventSuffix }}.window="open = true; bringToFront(); _keepInBounds(); focusFrame(); _save()"
    @magic-window-close-{{ $eventSuffix }}.window="endAction(); open = false; _flushSaveNow()"
    @magic-window-toggle-{{ $eventSuffix }}.window="open = !open; if (open) { bringToFront(); _keepInBounds(); focusFrame(); _save() } else { _flushSaveNow() }"

    {{-- Window alignment events --}}
    @magic-window-align-left-{{ $eventSuffix }}.window="alignLeft()"
    @magic-window-align-right-{{ $eventSuffix }}.window="alignRight()"
    @magic-window-align-x-center-{{ $eventSuffix }}.window="alignXCenter()"
    @magic-window-align-top-{{ $eventSuffix }}.window="alignTop()"
    @magic-window-align-bottom-{{ $eventSuffix }}.window="alignBottom()"
    @magic-window-align-y-center-{{ $eventSuffix }}.window="alignYCenter()"

    {{-- External setting for window width --}}
    @magic-window-set-width-{{ $eventSuffix }}.window="
        if ($event.detail && typeof $event.detail.width !== 'undefined') {
            setWidthPercent($event.detail.width);
        }
    "

    {{-- Reboot events (global + gezielt pro Window) --}}
    @magic-window-reboot.window="reboot()"
    @magic-window-reboot-{{ $eventSuffix }}.window="reboot()"

    {{-- Titlebar visibility events --}}
    @magic-window-titlebar-show-{{ $eventSuffix }}.window="titlebarVisible = true"
    @magic-window-titlebar-hide-{{ $eventSuffix }}.window="titlebarVisible = false"
>
    {{-- Frame --}}
    <div x-cloak x-ref="frame" tabindex="-1" role="dialog" :aria-labelledby="mwTitleId"
         style="{{ $fallbackStyle }}" :style="frameStyle()"
         class="pointer-events-auto flex flex-col overflow-hidden outline-none {{ $layoutWindowBackground }} {{ $layoutWindowShadow }} group"
         :class="frameClass()"
         @pointerdown.stop="
            bringToFront();
            const t = $event.target;
            const interactive = t.closest('select, input, textarea, button, [contenteditable=true], .no-refocus, .no-drag');
            if (!interactive) {
                $nextTick(() => { if ($refs.frame) { try { $refs.frame.focus({ preventScroll: true }) } catch(_) { $refs.frame.focus() } } })
            }"
         @focusin="bringToFront()"
    >

        {{-- Title bar --}}
        <div
            x-show="titlebarVisible"
            class="{{ $layoutWindowTitleBar }} w-full flex flex-row items-center gap-x-1 justify-between select-none touch-none flex-none"
            :class="isFree() ? 'cursor-move' : 'cursor-default'"
            @pointerdown.capture="if(isFree() && !isInteractive($event.target)){ $event.preventDefault(); startDrag($event); }"
            @pointerup="dragging=false"
        >
            <div class="w-full flex flex-row items-center gap-x-1 ml-1">
                @if($iconName)
                    <x-dynamic-component :component="$iconName" class="{{ $layoutWindowTitleBarIcon }}"/>
                @endif
                @if(isset($titlebarContent) && $titlebarContent->isNotEmpty())
                    {{ $titlebarContent }}
                @endif
                @if($title)
                    <div :id="mwTitleId">{{ $title }}</div>
                @endif
            </div>

            <div>
                <template x-if="workingMode === 'window'">
                    <div class="{{ $layoutWindowTitlebarButtonGroup }}">
                        <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag" @pointerdown.stop
                                @click.stop="togglePin()" :title="'{{ __('Pin') }}'">
                            {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!! $iconPin!!}{!!$iconSuffix!!}
                        </button>
                        <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag" @pointerdown.stop
                                @click.stop="toggleDock()" :title="'{{ __('Dock') }}'">
                            {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!! $iconAnchor!!}{!!$iconSuffix!!}
                        </button>
                        @if($showResetButton)
                            <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag"
                                    @pointerdown.stop @click.stop="resetToDefaultWindow()"
                                    :title="'{{ __('Reset size') }}'">
                                {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!!$iconAutofit!!}{!!$iconSuffix!!}
                            </button>
                        @endif
                        <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag mr-2"
                                @pointerdown.stop @click.stop="endAction(); open=false; _flushSaveNow()"
                                :title="'{{ __('Close') }}'">
                            {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!! $iconSquareX!!}{!!$iconSuffix!!}
                        </button>
                    </div>
                </template>

                <template x-if="workingMode === 'window-pinned'">
                    <div class="{{ $layoutWindowTitlebarButtonGroup }}">
                        <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag" @pointerdown.stop
                                @click.stop="setMode('window')" :title="'{{ __('Unpin') }}'">
                            {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!!$iconPinOff!!}{!!$iconSuffix!!}
                        </button>
                        @if($showResetButton)
                            <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag"
                                    @pointerdown.stop @click.stop="resetToDefaultWindow()"
                                    :title="'{{ __('Reset size') }}'">
                                {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!!$iconAutofit!!}{!!$iconSuffix!!}
                            </button>
                        @endif
                        <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag mr-2"
                                @pointerdown.stop @click.stop="endAction(); open=false; _flushSaveNow()"
                                :title="'{{ __('Close') }}'">
                            {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!! $iconSquareX!!}{!!$iconSuffix!!}
                        </button>
                    </div>
                </template>

                <template x-if="workingMode === 'window-anchored'">
                    <div class="{{ $layoutWindowTitlebarButtonGroup }}">
                        <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag" @pointerdown.stop
                                @click.stop="setMode('window')" :title="'{{ __('Undock') }}'">
                            {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!! $iconAnchorOff!!}{!!$iconSuffix!!}
                        </button>
                        @if($showResetButton)
                            <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag"
                                    @pointerdown.stop @click.stop="resetToDefaultWindow()"
                                    :title="'{{ __('Reset size') }}'">
                                {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!!$iconAutofit!!}{!!$iconSuffix!!}
                            </button>
                        @endif
                        <button type="button" class="{{ $layoutWindowTitleBarButton }} no-drag mr-2"
                                @pointerdown.stop @click.stop="endAction(); open=false; _flushSaveNow()"
                                :title="'{{ __('Close') }}'">
                            {!!$iconPrefixPre!!}{!!$layoutWindowTitleBarButtonIcon!!}{!!$iconPrefixPost!!}{!! $iconSquareX!!}{!!$iconSuffix!!}
                        </button>
                    </div>
                </template>
            </div>

        </div>

        {{-- Body --}}
        <div x-ref="body" class="w-full h-fit overflow-y-auto {{ $layoutWindowBody }}"
             style="-webkit-overflow-scrolling: touch; scrollbar-gutter: auto;">
            {{ $slot }}
        </div>

        {{-- Resize handles --}}
        <template x-if="isFree() && open">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-0 h-2 w-full pointer-events-auto cursor-n-resize touch-none"
                     @pointerdown.prevent.stop="startResize($event,'n')"></div>
                <div class="absolute bottom-0 left-0 h-2 w-full pointer-events-auto cursor-s-resize touch-none"
                     @pointerdown.prevent.stop="startResize($event,'s')"></div>
                <div class="absolute top-0 left-0 w-2 h-full pointer-events-auto cursor-w-resize touch-none"
                     @pointerdown.prevent.stop="startResize($event,'w')"></div>
                <div class="absolute top-0 right-0 w-2 h-full pointer-events-auto cursor-e-resize touch-none"
                     @pointerdown.prevent.stop="startResize($event,'e')"></div>
                <div class="absolute top-0 left-0 w-4 h-4 pointer-events-auto cursor-nwse-resize touch-none"
                     @pointerdown.prevent.stop="startResize($event,'nw')"></div>
                <div class="absolute top-0 right-0 w-4 h-4 pointer-events-auto cursor-nesw-resize touch-none"
                     @pointerdown.prevent.stop="startResize($event,'ne')"></div>
                <div class="absolute bottom-0 left-0 w-4 h-4 pointer-events-auto cursor-nesw-resize touch-none"
                     @pointerdown.prevent.stop="startResize($event,'sw')"></div>
                <div class="absolute bottom-0 right-0 w-4 h-4 pointer-events-auto cursor-nwse-resize touch-none"
                     @pointerdown.prevent.stop="startResize($event,'se')"></div>
            </div>
        </template>
    </div>
</div>
