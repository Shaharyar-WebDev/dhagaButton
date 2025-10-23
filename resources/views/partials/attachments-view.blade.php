<div class="p-4">
    @if (empty($attachments))
        <div class="text-center py-10 text-gray-400">
            <x-heroicon-o-photo class="w-10 h-10 mx-auto mb-2" />
            <p>No attachments uploaded.</p>
        </div>
    @else
        @php
            $count = count($attachments);
            $gridCols = match (true) {
                $count === 1 => 'grid-cols-1',
                $count === 2 => 'grid-cols-2',
                $count <= 4 => 'grid-cols-2 sm:grid-cols-2 md:grid-cols-4',
                default => 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5',
            };
        @endphp

        <div class="grid {{ $gridCols }} gap-4">
            @foreach ($attachments as $path)
                <div class="relative group overflow-hidden rounded-xl shadow-md bg-gray-100">
                    <img src="{{ $path }}" alt="Attachment"
                        class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105 {{ $count === 1 ? 'aspect-auto max-h-[70vh]' : 'aspect-square' }}">
                    <div style="margin-top: 8px !important;" class="flex justify-center items-center gap-4">
                        <a href="{{ $path }}" target="_blank" class="fi-ac-btn fi-btn">
                            <x-heroicon-o-eye class="w-5 h-5" /> View
                        </a>
                        <div style="width:8px;"></div>
                        <a href="{{ $path }}" download class="fi-ac-btn fi-btn rounded-full">
                            <x-heroicon-o-arrow-down-tray class="w-5 h-5" /> Download
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
