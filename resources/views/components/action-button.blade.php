@props(['type', 'url'])

@if($type === 'edit')
    <a href="{{ $url }}" class="inline-flex items-center gap-2 px-4 py-1 text-sm font-medium text-brand bg-search border border-brand rounded-md hover:bg-brand hover:text-white transition-colors duration-200">
        <i class="fas fa-pen text-xs"></i> Edit
    </a>
@elseif($type === 'delete')
    <form method="POST" action="{{ $url }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" title="Hapus Produk" class="w-8 h-8 flex items-center justify-center rounded-full bg-search text-brand border border-brand hover:bg-red-500 hover:text-white hover:border-red-500 transition-colors duration-200 shadow-sm">
            <i class="fas fa-trash text-sm"></i>
        </button>
    </form>
@endif