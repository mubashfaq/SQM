<style>
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .pagination ul {
        list-style: none;
        display: flex;
        padding: 0;
    }
    .pagination li {
        margin: 0 5px;
    }
    .pagination a, .pagination span {
        display: block;
        padding: 8px 12px;
        border: 1px solid #ccc;
        text-decoration: none;
        color: #333;
        border-radius: 5px;
    }
    .pagination a:hover {
        background-color: #f0f0f0;
    }
    .pagination .active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    .pagination .disabled {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }
</style>

@if ($paginator->hasPages())
    <nav class="pagination">
        <ul>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><span class="disabled">&laquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}">&laquo;</a></li>
            @endif
            
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="disabled">{{ $element }}</span></li>
                @endif
                
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="active">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}">&raquo;</a></li>
            @else
                <li><span class="disabled">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif