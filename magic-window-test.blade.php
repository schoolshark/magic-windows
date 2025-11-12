{{--magic-window-test.blade.php--}}

<div class="bg-zinc-50 dark:bg-zinc-600/90 text-zinc-950 dark:text-zinc-50">

    <div class="text-2xl p-4">Magic Windows | Demo</div>

    <a class="p-4 flex flex-row items-center justify-start gap-1" href="https://github.com/schoolshark/magic-windows">
        <x-tabler-brand-github class="w-6 h-6" /><div>https://github.com/schoolshark/magic-windows</div>
    </a>

    <div class="w-full flex flex-row items-center gap-1 p-4">
        {{-- Test-Buttons: magic-window will react to external commands--}}
        <button class="h-8 bg-sky-600/100 hover:bg-sky-600/90 dark:hover:bg-sky-600/90 text-white flex flex-row items-center p-2 rounded"
                onclick="window.dispatchEvent(new Event('magic-window-toggle-win1'))">
            Toggle win1
        </button>
        <button class="h-8 bg-sky-600/100 hover:bg-sky-600/90 dark:hover:bg-sky-600/90 text-white flex flex-row items-center p-2 rounded"
                onclick="window.dispatchEvent(new Event('magic-window-titlebar-hide-win1'))">
             win1 title-bar hide
        </button>
        <button class="h-8 bg-sky-600/100 hover:bg-sky-600/90 dark:hover:bg-sky-600/90 text-white flex flex-row items-center p-2 rounded"
                onclick="window.dispatchEvent(new Event('magic-window-titlebar-show-win1'))">
            win1 title-bar show
        </button>
        <button class="h-8 bg-pink-600/100 hover:bg-pink-600/90 dark:hover:bg-pink-600/90 text-white flex flex-row items-center p-2 rounded"
                onclick="window.dispatchEvent(new Event('magic-window-open-win2'))">
            Open win2
        </button>
        <button class="h-8 bg-pink-600/100 hover:bg-pink-600/90 dark:hover:bg-pink-600/90 text-white flex flex-row items-center p-2 rounded"
                onclick="window.dispatchEvent(new Event('magic-window-close-win2'))">
            Close win2
        </button>
        <button class="h-8 bg-zinc-400 hover:bg-lime-600/90 dark:hover:bg-lime-600/90 text-white flex flex-row items-center p-2 rounded"
                onclick="window.dispatchEvent(new Event('magic-window-local-storage-clear'))">
            Reset Local-Storage (Pos/Coord)
        </button>
        <button class="h-8 w-10 bg-zinc-400 hover:bg-lime-600/90 dark:hover:bg-lime-600/90 text-white flex flex-row items-center p-2 rounded"
            x-data="{d:document.children[0].classList.contains('dark'),dk:'{{ Storage::url('images/mode-dark-transparent.svg') }}',lt:'{{ Storage::url('images/mode-light-transparent.svg') }}'}"
            @click="d=!d;document.children[0].classList.toggle('dark');$refs.i.src=d?dk:lt;Livewire.dispatch('toggleDarkMode');return false">
            <img x-ref="i" class="iconsize paragraphs praxelnShadow" :src="d?dk:lt" alt="" >
        </Button>
    </div>

    <div class="w-full flex flex-col justify-center-center gap-1 p-4">
        <div class="font-semibold">This is a medium sized container. Your window is trapped inside</div>
        <div>Give your container an id, wide/height and wire:ignore.self to obtain a stable behaviour</div>
    </div>

    {{--  id, not needed but better: fixed hight ( --}}
    <div id="my-container" wire:ignore.self class="w-full h-[1080px] overflow-y-hidden">

        <x-magic-window

            {{-- Ensure unique window-id! ...--}}
            window-id="win1"
            title="{{__('My first magic window')}}"
            icon-name="tabler-world"

            :initial-open="true"
            default-wide="w-full md:w-1/4"

            {{-- Select a tailwind color-family: slate, gray, pink ...
             Here: basecolor == accentcolor ---> no accent color --}}
            basecolor="sky"
            accentcolor="sky"

            {{--  The initial working-mode --}}
            working-mode="window"
            :initial-open="true"

            {{--  keep inside the container --}}
            :clamp-x="false"
        >

            {{--  The content | Give it allways "w-full h-fit  --}}
            <div class="w-full h-fit flex flex-col items-start">
                <ul class="list-disc pl-5">
                    <li>You can put whole Views into this space</li>
                    <li>Even huge livewire or react-components</li>
                    <li>Livewire will update your component as usual</li>
                    <li>This window can be moved over the horizontal borders // :clamp-x="false"</li>
                </ul>
            </div>
        </x-magic-window>

        <x-magic-window

            window-id="win2"
            title="{{__('Test2')}}"
            icon-name="tabler-world"

            :initial-open="false"
            default-wide="w-full md:w-1/4"
            default-height="max-h-min"
            working-mode="window"

            basecolor="pink"
            accentcolor="pink"

            :clamp-x="true"
        >

            <x-slot:titlebarContent>
                <div class="bg-white text-black px-2 italic rounded">custom...</div>
            </x-slot:titlebarContent>

            {{--  The content | Give it allways "w-full h-fit  --}}
            <div class="w-full h-fit flex flex-col items-center justify-center p-2 bg-white text-black">
                <ul>
                    <li>You can toggle this with the buttons by event</li>
                    <li>This window can not be moved outside // :clamp-x="true"</li>
                </ul>
            </div>
        </x-magic-window>
    </div>


    {{-- If you use Vite: Use this honeypot to ensure
        that Vite includes your tailwind-classes.
        Activate / comment only the colors you need --}}
   @include('components.magic-window-honeypots')

</div>


