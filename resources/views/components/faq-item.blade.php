@props(['faq', 'isFirst' => false])

<div x-data="{ open: false }" 
     class="border-b border-gray-200 last:border-b-0">
    <button @click="open = !open"
            class="flex justify-between items-center w-full py-6 text-left">
            <span class="text-lg font-semibold text-gray-900">
            {{ $faq->question }}
        </span>
        <span x-bind:class="{'rotate-180': open}" class="transform transition-transform duration-200">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </span>
    </button>
    
    <div x-show="open"
         x-transition:enter="transition-all duration-200 ease-out"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition-all duration-200 ease-in"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="pb-6 prose prose-gray max-w-none">
        {!! $faq->answer !!}
    </div>
</div>