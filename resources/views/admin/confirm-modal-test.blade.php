@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <h1 class="text-3xl font-bold text-center text-gray-900 mb-8">Tes Confirm Modal</h1>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <p class="text-gray-700 mb-6">Klik tombol di bawah untuk membuka modal konfirmasi dan memastikan tampilan komponen bekerja dengan benar.</p>

        <button id="openConfirmBtn" type="button" class="px-6 py-3 bg-[#d4af37] text-white font-medium rounded-md hover:bg-[#b5952f] transition-colors shadow-sm">
            Buka Confirm Modal
        </button>
    </div>

    <x-confirm-modal
        id="submitConfirmModal"
        title="Konfirmasi Tampilan Modal"
        message="Ini adalah tampilan test untuk confirm modal. Apakah komponen modal sudah muncul seperti yang diharapkan?"
    />
</div>

<script>
    const modal = document.getElementById('submitConfirmModal');
    const openConfirmBtn = document.getElementById('openConfirmBtn');
    const cancelBtn = modal.querySelector('.modal-cancel-btn');
    const backdrop = modal.querySelector('.modal-backdrop');

    function closeModal() {
        modal.classList.add('hidden');
    }

    openConfirmBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
</script>
@endsection
