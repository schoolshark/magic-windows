# Magic Window Component

_multiple windows for Laravel Livewire: draggable, pinnable, resizable, persistable | batteries included :-)_

- Made as a monolithic single file component to allow quick and painless integration.
- A draggable, pinnable, and resizable window component built with Livewire and Alpine.js for Laravel applications.
- This component allows creating floating windows that can be dragged, resized, pinned as overlays, or anchored/docked.
- It supports persistence of position, size, mode, and (optionally) open-state and height via LocalStorage.
  

## Overview

- Author: Dr. Stefan Radolf & the Praxeln team
- Demo: https://stage.praxeln.de/magic-windows
- Version: 1.1.2
- License: MIT

> Praxeln is a central online platform for distributed management of clinical placements in nursing education. It connects students, training institutions, and practical training sites and supports placement planning, scheduling, messaging, and more. During the development of the Praxeln platform, many reusable Livewire components were created. Some of them – including this Magic Window – are released here under the MIT License in the hope that they will be useful to others.

## Requirements

- Laravel/Livewire or Laravel/Blade + Alpine.js
- Alpine.js ≥ 3.1
- Tailwind CSS
- Optional: Tabler Icons (for icon support)

## Installation

Copy the files to your `resources/views/components/` directory:

- `magic-window.blade.php`
- `magic-window-honeypots.blade.php` (contains Tailwind classes for Vite/Tailwind discovery)

Install Icons:  
https://github.com/ryangjchandler/blade-tabler-icons

Optionally you can use the test blade. Just include it inside a Livewire component (containing Alpine ≥ 3.1),
or in a standard Blade view (with Alpine ≥ 3.1 installed):

- `magic-window-test.blade.php` (two windows and some buttons to play with …)

## Usage

Include the component in your Blade views using the `<x-magic-window>` tag.  
Provide a unique `window-id` for each instance.

### Basic Example

```blade
{{-- Container: use fixed height and wire:ignore.self for stable behaviour --}}
<div id="my-container" wire:ignore.self class="w-full h-[1080px] overflow-y-hidden">

    <x-magic-window

        {{-- Ensure unique window-id! --}}
        window-id="win1"

        title="{{ __('My first magic window') }}"

        icon-name="tabler-world"

        :initial-open="true"

        {{-- Default width/height via Tailwind --}}
        default-wide="w-full md:w-1/4"
        default-height="h-fit"

        {{-- Select Tailwind color families: slate, gray, pink... --}}
        basecolor="sky"
        accentcolor="rose"

        {{-- Initial working mode --}}
        working-mode="window"

        {{-- keep inside the container? (false = can move beyond horizontally) --}}
        :clamp-x="false"

        {{-- enable persistence (optional) --}}
        :persist="true"
        :persist-open-state="true"
        :persist-height="false"
    >

        {{-- give your content "w-full h-fit" --}}
        <div class="w-full h-fit flex flex-col items-start">
            <ul class="list-disc pl-5">
                <li>You can put whole Views into this space</li>
                <li>Even huge Livewire or React components</li>
                <li>Livewire will update your component as usual</li>
                <li>This window can be moved over the horizontal borders // :clamp-x="false"</li>
            </ul>
        </div>

    </x-magic-window>

</div>
```

### Slots

- **Default Slot (`$slot`)**: The main body content of the window.
- **Titlebar Content**: If you need custom content in the title bar, pass it via a named slot:

```blade
<div id="my-container" wire:ignore.self class="w-full h-[1080px] overflow-y-hidden">

    <x-magic-window
        window-id="win2"
        title="{{ __('My second magic window') }}"
        icon-name="tabler-world"
        :initial-open="false"
        default-wide="w-3/4 md:w-1/4"
        default-height="max-h-min"
        working-mode="window"
        basecolor="pink"
        accentcolor="pink"
        :clamp-x="true"
    >

        <x-slot:titlebarContent>
            <div class="bg-white text-black px-2 italic rounded">
                custom title
            </div>
        </x-slot:titlebarContent>

        {{-- This is the Content-Slot | give your content "w-full h-fit" --}}
        <div class="w-full h-fit flex flex-col items-center justify-center p-2 bg-white text-black">
            <ul>
                <li>You can toggle this with the buttons by event</li>
                <li>This window can not be moved outside // :clamp-x="true"</li>
            </ul>
        </div>

    </x-magic-window>

</div>
```

## Modes

```text
workingMode = window | window-pinned | window-anchored
```

- `window`  
  Draggable and resizable within the parent container. Uses container-based coordinates.
- `window-pinned`  
  Fixed overlay on the viewport (HUD-like). Uses viewport-based coordinates.
- `window-anchored`  
  Docked/static in normal layout flow. No inline coordinates, relies on layout classes.

Mode transitions:

- `window ↔ window-pinned` convert between container and viewport coordinates.
- `window-anchored` only changes layout/size handling, not the persisted coordinates.

## Full List of Blade / Livewire Props

| Prop                         | Type     | Default              | Description                                                                                               |
|------------------------------|----------|----------------------|-----------------------------------------------------------------------------------------------------------|
| `windowId`                   | string   | `null`               | Required. Unique identifier for the window. Used for persistence and event suffixes.                      |
| `title`                      | string   | `''`                 | Title displayed in the title bar.                                                                         |
| `initialOpen`                | boolean  | `false`              | Initial visibility: `true` for open, `false` for closed.                                                  |
| `workingMode`                | string   | `'window'`           | Initial mode: `'window'` (draggable), `'window-pinned'` (overlay), `'window-anchored'` (docked).          |
| `titlebarType`               | string   | `'visible'`          | Title bar visibility: `'visible'` or `'hidden'`. Can be toggled via events.                               |
| `persist`                    | boolean  | `false`              | Store/load last position, size and mode in LocalStorage.                                                 |
| `persistOpenState`           | boolean  | `false`              | Also persist the open/closed state when `persist` is enabled.                                            |
| `persistHeight`              | boolean  | `false`              | Persist inline height, but only after a vertical resize occurred.                                        |
| `saveThrottleMs`             | integer  | `200`                | Throttle (ms) before saving state to LocalStorage.                                                       |
| `iconName`                   | string   | `null`               | Optional Tabler icon name for the title bar (e.g. `'tabler-plane-departure'`).                           |
| `initLeft`                   | string   | `'5%'`               | Initial left position (e.g. `'5%'`, `'10px'`, `'20vw'`).                                                 |
| `initTop`                    | string   | `'5%'`               | Initial top position (similar to `initLeft`).                                                            |
| `snap`                       | boolean  | `true`               | Enable snapping to container edges when dragging near them.                                              |
| `snapThreshold`              | integer  | `16`                 | Pixel threshold for snapping.                                                                            |
| `viewportMargin`             | integer  | `0`                  | Margin in pixels from viewport/container edges.                                                          |
| `magicWindowIndexCounterStart` | integer | `2000`              | Base z-index for all Magic Windows (global counter).                                                     |
| `clampX`                     | boolean  | `false`              | Clamp horizontal position to prevent leaving the parent container.                                       |
| `showResetButton`            | boolean  | `false`              | Show a reset button in the title bar to restore default size/position.                                   |
| `defaultWide`                | string   | `'w-max md:w-3/4'`   | Default width Tailwind classes when no inline width is active.                                           |
| `defaultHeight`              | string   | `'h-fit'`            | Default height Tailwind classes when no inline height is active.                                         |
| `basecolor`                  | string   | `'zinc'`             | Base color basename for Tailwind (e.g. `'zinc'` for `bg-zinc-500`).                                      |
| `accentcolor`                | string   | `'pink'`             | Accent color basename for Tailwind.                                                                      |
| `localStorageNotice`         | string   | `'Window Settings cleaned'` | Message shown after a global LocalStorage reset.                                               |

### Note on Colors

Provide Tailwind color basenames (e.g. `zinc`, `amber`).  
The component injects them into layout classes via placeholders `basecolor` and `accentcolor`.  
Use the same base and accent for subtle effects. Use the honeypots file so your bundler picks up all classes.

## Dispatch events to control the component

Replace `{suffix}` with the slugified `windowId`, e.g. `win1`.

### Open / Close / Toggle

```js
window.dispatchEvent(new Event('magic-window-open-{suffix}'));
window.dispatchEvent(new Event('magic-window-close-{suffix}'));
window.dispatchEvent(new Event('magic-window-toggle-{suffix}'));
```

### Titlebar visibility

```js
window.dispatchEvent(new Event('magic-window-titlebar-show-{suffix}'));
window.dispatchEvent(new Event('magic-window-titlebar-hide-{suffix}'));
```

### Alignment helpers (always switch to `window` mode first)

```js
window.dispatchEvent(new Event('magic-window-align-left-{suffix}'));
window.dispatchEvent(new Event('magic-window-align-right-{suffix}'));
window.dispatchEvent(new Event('magic-window-align-x-center-{suffix}'));
window.dispatchEvent(new Event('magic-window-align-top-{suffix}'));
window.dispatchEvent(new Event('magic-window-align-bottom-{suffix}'));
window.dispatchEvent(new Event('magic-window-align-y-center-{suffix}'));
```

### Width control (percentage of container width in `window` mode)

Example: set width to 25 % of the parent container:

```js
window.dispatchEvent(
    new CustomEvent('magic-window-set-width-{suffix}', {
        detail: { width: 25 }
    })
);
```

This switches to inline width mode, applies min/max constraints, and keeps the window in bounds.

### Global reset for all Magic Windows

Remove all persisted Magic Window entries from LocalStorage and show a notice:

```js
window.dispatchEvent(new Event('magic-window-local-storage-clear'));
```

(Older alias for compatibility: `recke-reset-windows`.)

### Reboot (re-initialize using defaults + persisted state)

Reboot all Magic Windows:

```js
window.dispatchEvent(new Event('magic-window-reboot'));
```

Reboot a single Magic Window:

```js
window.dispatchEvent(new Event('magic-window-reboot-{suffix}'));
```

Behaviour:

- Restores internal state to the original props:
  - `workingMode` → initial `workingMode`
  - `open` → initial `initialOpen`
  - positions → `initLeft` / `initTop`
  - clears inline width/height and vertical resize flag
- Then reloads any available persisted state (if `persist` is `true`).
- If called after `magic-window-local-storage-clear`, the window uses pure defaults.

You can combine both:

```js
window.dispatchEvent(new Event('magic-window-local-storage-clear'));
window.dispatchEvent(new Event('magic-window-reboot'));
```

…to fully reset layout to defaults.

### Simulated extra “modes”

You can combine a working mode with events to simulate two additional behaviours:

- **Modal**  
  `workingMode = "window"` + event `magic-window-titlebar-hide`  
  → fullscreen/modal-like content window without title bar.

- **Inline div**  
  `workingMode = "window-anchored"` + event `magic-window-titlebar-hide`  
  → behaves like a regular `<div>Content</div>` inlined in your container(s).

## Persistence

When `persist` is true, position, size, mode, and resize history are saved in LocalStorage under:

```text
praxeln.magic-window.{key}
```

`{key}` is derived from the component’s `windowId`.

Stored data (simplified):

- `open` (if `persistOpenState` is enabled)
- `workingMode`
- `meta.hasVerticalResize` (if `persistHeight` is enabled)
- `free` (for `window` mode): `x`, `y`, `w`, `h`, `useInlineSize`
- `pinned` (for `window-pinned` mode): `vx`, `vy`, `w`, `h`, `useInlineSize`

`window-anchored` uses normal layout flow and does not store coordinates.

Dispatch `'magic-window-local-storage-clear'` to remove all persisted entries.  
Use `'magic-window-reboot'` (or `'magic-window-reboot-{suffix}'`) afterwards to re-initialize windows.

## Styling

The component uses Tailwind classes extensively and supports dark mode in many defaults.  
Customize via props like `layoutWindowBody`, `layoutWindowTitleBar`, `layoutWindowBorder`, etc.

`magic-window-honeypots.blade.php` contains a full set of Tailwind classes for all supported color families so your bundler includes them.

## Advanced Features

- Z-Index Management: Global counter to bring windows to front (based on `magicWindowIndexCounterStart`).
- Snapping: Windows snap to edges when within `snapThreshold`.
- Resizing: Smart resizing in `window` mode with min/max width and height and container-aware bounds.
- Anchored mode: `window-anchored` behaves like a docked panel or inline `div` but is still controlled via events.
- Keyboard: `Esc` closes the window and persists final state (this should be disabled or handled carefully in forms to prevent accidentally closed windows).
