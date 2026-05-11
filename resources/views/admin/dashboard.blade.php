@extends('layouts.app')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 mt-4 gap-4">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Daftar Produk</h1>
        
        <x-button 
            type="primary" 
            label="+ Produk" 
            href="{{ route('admin.products.create') }}" 
            class="rounded-md px-6 shadow-sm" 
        />
    </div>

    @if(empty($products))
        <div class="w-full py-16 flex flex-col items-center justify-center text-center bg-gray-50 rounded-xl border border-gray-200">
            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700">Belum ada produk</h3>
            <p class="text-gray-500 mt-2 mb-4">Silakan tambah produk baru untuk mulai berjualan.</p>
            <x-button type="primary" label="+ Tambah Produk Sekarang" href="{{ route('admin.products.create') }}" />
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <x-admin-product-card :product="$product" />
            @endforeach
        </div>
    @endif

    <div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity modal-backdrop" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-trash text-red-600 text-lg"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                Konfirmasi Hapus Produk
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="delete-modal-message">
                                    Apakah Anda yakin ingin menghapus produk ini? Produk akan dihapus secara permanen.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 transition-colors focus:outline-none sm:ml-3 sm:w-auto sm:text-sm modal-confirm-btn">
                        Ya, hapus
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm modal-cancel-btn">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteConfirmBtn = deleteModal?.querySelector('.modal-confirm-btn');
        const deleteCancelBtn = deleteModal?.querySelector('.modal-cancel-btn');
        const deleteModalMessage = document.getElementById('delete-modal-message');
        let deleteTargetForm = null;

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            deleteTargetForm = null;
        }

        document.querySelectorAll('form.delete-product-form').forEach((form) => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                deleteTargetForm = this;
                
                // Get product name from data attribute
                const productName = this.dataset.productName || 'produk ini';
                deleteModalMessage.textContent = `Apakah Anda yakin ingin menghapus produk "${productName}"? Produk akan dihapus secara permanen.`;
                
                deleteModal.classList.remove('hidden');
            });
        });

        if (deleteConfirmBtn) {
            deleteConfirmBtn.addEventListener('click', function() {
                if (deleteTargetForm) {
                    deleteTargetForm.submit();
                }
            });
        }

        if (deleteCancelBtn) {
            deleteCancelBtn.addEventListener('click', closeDeleteModal);
        }
    </script>

@endsection