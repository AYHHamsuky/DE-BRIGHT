@if ($paginator->hasPages())
    <div class="pager">
        @if ($paginator->onFirstPage())
            <span class="btn sm alt" aria-disabled="true" style="opacity:.5">&larr; Previous</span>
        @else
            <a class="btn sm alt" href="{{ $paginator->previousPageUrl() }}">&larr; Previous</a>
        @endif
        <span>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }} &middot; {{ $paginator->total() }} total</span>
        @if ($paginator->hasMorePages())
            <a class="btn sm alt" href="{{ $paginator->nextPageUrl() }}">Next &rarr;</a>
        @else
            <span class="btn sm alt" aria-disabled="true" style="opacity:.5">Next &rarr;</span>
        @endif
    </div>
@endif
