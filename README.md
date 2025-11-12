# Magic Window Component

// multiple windows for laravel livewire: draggable, pinnable, resizable, persistable | batteries included :-)

A draggable, pinnable, and resizable window component built with Livewire and Alpine.js for Laravel applications. This component allows creating floating windows that can be dragged, resized, pinned as overlays, or anchored/docked. It supports persistence of position, size, and mode via LocalStorage.

## Overview

- Author: Dr. Stefan Radolf & the Praxeln team
- Demo: [https://stage.praxeln.de/magic-windows](https://stage.praxeln.de/magic-windows)
- License: MIT

// About: Praxeln is a central online platform for distributed management of clinical placements in nursing education. It connects students, training institutions, and practical training sites and supports placement planning, scheduling, messaging, and more. During the development of the Praxeln platform, many reusable Livewire components were created. Some of them – including this Magic Window – are released here under the MIT License in the hope that they will be useful to others

## Requirements

- Laravel/Livewire or Laravel/Blade+Alpine.js
- Tailwind CSS
- Optional: Tabler Icons (for icon support)


## Installation

Copy the files to your `resources/views/components/` directory:

- `magic-window.blade.php` 
- `magic-window-honeypots.blade.php` // contains tailwind-classes for vite

Install Icons:
https://github.com/ryangjchandler/blade-tabler-icons

Optional you can use this test-blade. Just include it inside a livewire component (containing alpine > Ver. 3.1),
or in a standard blade-component (with alpine > Ver. 3.1 istalled)
- `magic-window-test.blade.php` // two windows an some buttons to play with ...

## Usage

Include the component in your Blade views using the ```<x-magic-window>``` tag. 
Provide a unique `window-id` for each instance.

### Basic Example

```blade
{{--  id, not needed but better: fixed hight ( --}}
<div id="my-container" wire:ignore.self class="w-full h-[1080px] overflow-y-hidden">

      <x-magic-window

            {{-- Ensure unique window-id! ...--}}
            window-id="win1"

            title="{{__('My first magic window')}}"
            
            icon-name="tabler-world"

            :initial-open="true"

            default-wide="w-full md:w-1/4"

            {{-- Select tailwind color-families: slate, gray, pink... --}}

            basecolor="sky"
            accentcolor="rose"

            {{--  The initial working-mode --}}
            working-mode="window"

            :initial-open="true"

            {{--  keep inside the container --}}
            :clamp-x="false"
        >

            {{--  give your Content "w-full h-fit  --}}
            <div class="w-full h-fit flex flex-col items-start">
                <ul class="list-disc pl-5">
                    <li>You can put whole Views into this space</li>
                    <li>Even huge livewire or react-components</li>
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
            title="{{__('My second magic window')}}"

                  <x-slot:titlebarContent>
                            <div class="bg-white text-black px-2 italic rounded">custom title</div>
                  </x-slot:titlebarContent>

                  {{--  This is the Content-Slot | give your Content "w-full h-fit  --}}
                  <div class="w-full h-fit flex flex-col items-center justify-center p-2 bg-white text-black">
                      <ul>
                          <li>You can toggle this with the buttons by event</li>
                          <li>This window can not be moved outside // :clamp-x="true"</li>
                      </ul>
                  </div>

        </x-magic-window>

</div>
```

### Modes

{{--  The initial working-mode --}}
workingMode = window | window-pinned | window-anchored

- "window"  // Draggable and resizable within parent.
- "window-pinned" // Fixed overlay on viewport.
- "window-anchored"  // Docked/static in flow.

## Full List of Blade-Properties / Livewire-Properties

| Prop                           | Type     | Default                                                                                                                   | Description                                                                                                                     |
|--------------------------------|----------|---------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------|
| windowId                       | string   | `null`                                                                                                                    | Required. A unique identifier for the window. Used for persistence and event suffixes.                                          |
| title                          | string   | `''`                                                                                                                      | The title displayed in the title bar.                                                                                           |
| initialOpen                    | boolean  | `false`                                                                                                                   | Initial visibility: `true` for open, `false` for closed.                                                                        |
| workingMode                    | string   | `'window'`                                                                                                                | Initial mode: `'window'` (draggable), `'window-pinned'` (overlay), `'window-anchored'` (docked).                                |
| titlebarType                   | string   | `'visible'`                                                                                                               | Title bar visibility: `'visible'` or `'hidden'`.                                                                                |
| persist                        | boolean  | `false`                                                                                                                   | Automatically store/load last position, size, and mode in LocalStorage.                                                         |
| saveThrottleMs                 | integer  | `200`                                                                                                                     | Throttle time in milliseconds before saving state to LocalStorage.                                                              |
| iconName                       | string   | `null`                                                                                                                    | Optional Tabler icon name for the title bar (e.g., `'tabler-plane-departure'`).                                                 |
| initLeft                       | string   | `'5%'`                                                                                                                    | Initial left position (e.g., `'5%'`, `'10px'`, `'20vw'`).                                                                       |
| initTop                        | string   | `'5%'`                                                                                                                    | Initial top position (similar to `initLeft`).                                                                                   |
| snap                           | boolean  | `true`                                                                                                                    | Enable snapping to edges when dragging near them.                                                                               |
| snapThreshold                  | integer  | `16`                                                                                                                      | Pixel threshold for snapping.                                                                                                   |
| viewportMargin                 | integer  | `0`                                                                                                                       | Margin in pixels from viewport/container edges.                                                                                 |
| magicWindowIndexCounterStart   | integer  | `2000`                                                                                                                    | Base z-index for all Magic Windows.                                                                                             |
| clampX                         | boolean  | `false`                                                                                                                   | Clamp horizontal position to prevent leaving the parent container.                                                              |
| showResetButton                | boolean  | `false`                                                                                                                   | Show a reset button in the title bar to restore defaults.                                                                       |
| defaultWide                    | string   | `'w-max md:w-3/4'`                                                                                                        | Default width Tailwind classes.                                                                                                 |
| defaultHeight                  | string   | `'h-fit'`                                                                                                                 | Default height Tailwind classes.                                                                                                |
| basecolor                      | string   | `'zinc'`                                                                                                                  | Base color basename for Tailwind (e.g., `'zinc'` for `bg-zinc-500`).                                                            |
| accentcolor                    | string   | `'pink'`                                                                                                                  | Accent color basename for Tailwind.                                                                                             |

### Note on Colors:
Provide Tailwind color basenames (e.g., 'zinc', 'amber'). The component injects them into layout classes for proper contrast. Use the same base and accent for subtle effects. Include honeypots in your bundler config to pick up all classes.

## Dispatch events to controll the component

Replace {suffix} with the slugified windowId, e.g. win1.

// Open / Close / Toggle
- ```window.dispatchEvent(new Event('magic-window-open-{suffix}'))```
- ```window.dispatchEvent(new Event('magic-window-close-{suffix}'))```
- ```window.dispatchEvent(new Event('magic-window-toggle-{suffix}'))```

// Titlebar visibility
- ```window.dispatchEvent(new Event('magic-window-titlebar-show-{suffix}'))```
- ```window.dispatchEvent(new Event('magic-window-titlebar-hide-{suffix}'))```

// Global reset for all Magic Windows
- ```window.dispatchEvent(new Event('magic-window-local-storage-clear'))```

You can combine a workingMode with events to simulate two more workingModes:
- modal: window + Event magic-window-titlebar-hide // modal with your content:
- div: window-anchored + Event magic-window-titlebar-hide // like a regular ```<div>Content</div>```, inlined within your container(s):

## Persistence

When `persist` is true, position, size, mode, and resize history are saved in LocalStorage under:

- ```praxeln.magic-window.{key}```

-```{key}``` is derived from the component’s ```windowId```

- Dispatch 'magic-window-local-storage-clear' to reset persisted windows

## Styling

The component uses Tailwind classes extensively and supports dark mode in many defaults. Customize via props like ```layoutWindowBody```, ```layoutWindowTitleBar```, ```layoutWindowBorder```, etc.

- magic-window-honeypots.blade.php contains full set of all needed tailwind-classes to allow all of the 22 Tailwind Color-Families.

## Advanced Features ...

- Z-Index Management: Global counter to bring windows to front.
- Snapping: Windows snap to edges when within `snapThreshold`.
- Resizing: smart resizing in `window` mode.
- Keyboard: `Esc` closes the window // this should be disabled in Forms to prevent incidently closed windows....
  
