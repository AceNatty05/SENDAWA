@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-slate-800 border border-slate-700 rounded-lg cursor-default">
                    &laquo; Sebelumnya
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 transition-colors">
                    &laquo; Sebelumnya
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled"
                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 transition-colors">
                    Selanjutnya &raquo;
                </button>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-500 bg-slate-800 border border-slate-700 rounded-lg cursor-default">
                    Selanjutnya &raquo;
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-500">
                    Menampilkan
                    <span class="font-medium text-slate-300">{{ $paginator->firstItem() }}</span>
                    –
                    <span class="font-medium text-slate-300">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-medium text-slate-300">{{ $paginator->total() }}</span>
                    review
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-lg overflow-hidden">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-600 bg-slate-800 border border-slate-700 cursor-default">
                            &lsaquo;
                        </span>
                    @else
                        <button wire:click="previousPage" wire:loading.attr="disabled"
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-colors">
                            &lsaquo;
                        </button>
                    @endif

                    {{-- Pages --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-slate-800 border border-slate-700 cursor-default">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-violet-600 border border-violet-600 cursor-default">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-colors">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" wire:loading.attr="disabled"
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-colors">
                            &rsaquo;
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-600 bg-slate-800 border border-slate-700 cursor-default">
                            &rsaquo;
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
