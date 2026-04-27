@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    
    <h1 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-8 mt-4">Data Produk</h1>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="flex flex-col md:flex-row rounded-2xl overflow-hidden shadow-sm border border-gray-200">
            
            <div class="w-full md:w-1/2 bg-gray-100 p-8 flex flex-col items-center justify-center min-h-[400px] relative">
                
                <input type="file" name="image" id="imageInput" class="hidden" accept="image/*">
                
                <label for="imageInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-xl hover:bg-gray-200 transition-colors relative overflow-hidden group aspect-square max-w-sm">
                    
                    <div id="imagePlaceholder" class="flex flex-col items-center">
                        <i class="fas fa-image text-8xl text-gray-300 mb-4 group-hover:text-gray-400"></i>
                        <span class="text-sm text-gray-500 font-medium">Klik untuk upload gambar</span>
                    </div>

                    <img id="imagePreview" src="" alt="Preview" class="absolute inset-0 w-full h-full object-cover hidden">
                </label>
                @error('image') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 mt-2">Catatan: gambar utama tidak dapat dipulihkan otomatis. Jika halaman ter-refresh atau validasi gagal, unggah ulang gambar utama.</p>
            </div>

            <div class="w-full md:w-1/2 bg-[#fdf8dd] p-8 md:p-10 flex flex-col">
                
                <div class="space-y-6 flex-grow">
                    
                    <div>
                        <input type="text" name="title" placeholder="Nama Produk" required class="w-full bg-transparent border-b border-gray-400 pb-2 text-gray-800 placeholder-gray-500 focus:outline-none focus:border-yellow-600 transition-colors" value="{{ old('title') }}">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <textarea name="description" placeholder="Deskripsi" rows="2" class="w-full bg-transparent border-b border-gray-400 pb-2 text-gray-800 placeholder-gray-500 focus:outline-none focus:border-yellow-600 transition-colors resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <textarea name="specification" placeholder="• Spesifikasi\n• " rows="3" class="w-full bg-transparent border-b border-gray-400 pb-2 text-gray-800 placeholder-gray-500 focus:outline-none focus:border-yellow-600 transition-colors resize-none">{{ old('specification') }}</textarea>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <input type="text" name="location" placeholder="Lokasi (ex: Jakarta Selatan)" required class="w-full bg-transparent border-b border-gray-400 pb-2 text-gray-800 placeholder-gray-500 focus:outline-none focus:border-yellow-600 transition-colors" value="{{ old('location') }}">
                            @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 border border-gray-400 rounded-lg p-4">
                        <h4 class="text-sm font-bold text-gray-700 mb-4">Varian Produk (Wajib Min. 1)</h4>
                        <div id="variantsContainer" class="space-y-3"></div>

                        <p class="text-xs text-gray-500 mt-3">Catatan: file gambar varian tidak bisa dipulihkan otomatis. Jika halaman ter-refresh atau validasi gagal, unggah ulang file gambar varian.</p>

                        <div class="flex justify-center mt-4">
                            <button type="button" id="addVariantBtn" class="text-gray-600 hover:text-yellow-600 transition-colors">
                                <i class="fas fa-plus-circle text-xl"></i> Tambah Varian
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="reset" id="resetFormBtn" class="px-6 py-2 border border-yellow-500 text-yellow-600 font-medium rounded-md hover:bg-yellow-50 transition-colors">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2 bg-[#d4af37] text-white font-medium rounded-md hover:bg-[#b5952f] transition-colors shadow-sm">
                        Simpan
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    const DRAFT_KEY = 'admin:create-product:draft';
    const form = document.querySelector('form');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const addVariantBtn = document.getElementById('addVariantBtn');
    const variantsContainer = document.getElementById('variantsContainer');
    const resetFormBtn = document.getElementById('resetFormBtn');

    const serverDraft = {
        title: @json(old('title', null)),
        description: @json(old('description', null)),
        specification: @json(old('specification', null)),
        location: @json(old('location', null)),
        variants: @json(old('variants', [])),
    };

    // Store file objects per variant by index
    const variantFiles = new Map();

    function getSavedDraft() {
        try {
            const raw = localStorage.getItem(DRAFT_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (error) {
            console.warn('Draft load gagal:', error);
            return null;
        }
    }

    function saveDraft() {
        const draft = collectDraftData();
        localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
    }

    function clearDraft() {
        localStorage.removeItem(DRAFT_KEY);
        variantFiles.clear();
    }

    function collectDraftData() {
        const data = {
            title: form.querySelector('input[name="title"]').value || '',
            description: form.querySelector('textarea[name="description"]').value || '',
            specification: form.querySelector('textarea[name="specification"]').value || '',
            location: form.querySelector('input[name="location"]').value || '',
            variants: [],
        };

        variantsContainer.querySelectorAll('.variant-row').forEach((row, index) => {
            const nameInput = row.querySelector('input[name$="[name]"]');
            const priceInput = row.querySelector('input[name$="[price]"]');
            const stockInput = row.querySelector('input[name$="[stock]"]');
            const imagePreview = row.querySelector('.variant-image-preview');

            data.variants.push({
                name: nameInput?.value || '',
                price: priceInput?.value || '',
                stock: stockInput?.value || '',
                imageDataUrl: imagePreview?.src && imagePreview.src.startsWith('data:') ? imagePreview.src : null,
            });
        });

        return data;
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function buildVariantRow(variant = {}, index = 0) {
        const row = document.createElement('div');
        row.className = 'variant-row flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm';
        row.dataset.index = index;
        row.innerHTML = `
            <div class="w-14 h-14 shrink-0 relative border-2 border-dashed border-gray-300 rounded overflow-hidden hover:bg-gray-50 cursor-pointer flex items-center justify-center bg-gray-100 group">
                <input type="file" name="variants[${index}][image]" class="absolute inset-0 opacity-0 cursor-pointer variant-image-input" accept="image/*" data-index="${index}">
                <img src="${escapeHtml(variant.imageDataUrl || '')}" class="w-full h-full object-cover ${variant.imageDataUrl ? '' : 'hidden'} variant-image-preview">
                <i class="fas fa-camera text-gray-400 text-lg group-hover:text-yellow-600 variant-image-placeholder ${variant.imageDataUrl ? 'hidden' : ''} transition-colors"></i>
            </div>
            <input type="text" name="variants[${index}][name]" placeholder="Nama Varian (M, L, XL)" required class="flex-1 bg-transparent border-b border-gray-400 text-sm pb-1 focus:outline-none focus:border-yellow-600" value="${escapeHtml(variant.name)}">
            <div class="flex-1 flex items-center border-b border-gray-400">
                <span class="text-xs text-gray-500 mr-1">Rp</span>
                <input type="number" name="variants[${index}][price]" placeholder="Harga" required class="w-full bg-transparent text-sm pb-1 focus:outline-none" value="${escapeHtml(variant.price)}">
            </div>
            <div class="w-20 flex items-center border-b border-gray-400">
                <input type="number" name="variants[${index}][stock]" placeholder="Stok" required class="w-full bg-transparent text-sm pb-1 focus:outline-none text-center" value="${escapeHtml(variant.stock)}">
            </div>
            <button type="button" class="text-red-400 hover:text-red-600 remove-variant-btn ml-2">
                <i class="fas fa-trash"></i>
            </button>
        `;

        bindVariantRowEvents(row);
        return row;
    }

    function renumberVariants() {
        // Renumber all variant form field names to ensure sequential indexes
        variantsContainer.querySelectorAll('.variant-row').forEach((row, newIndex) => {
            row.dataset.index = newIndex;
            
            // Update all input names for this variant
            row.querySelectorAll('input[type="text"], input[type="number"], input[type="file"]').forEach(input => {
                const oldName = input.getAttribute('name');
                if (oldName) {
                    const newName = oldName.replace(/variants\[\d+\]/, `variants[${newIndex}]`);
                    input.setAttribute('name', newName);
                }
                if (input.type === 'file') {
                    input.dataset.index = newIndex;
                }
            });
        });
    }

    function rebuildVariantRows(variants = []) {
        variantsContainer.innerHTML = '';
        const source = variants.length ? variants : [{ name: '', price: '', stock: '' }];
        source.forEach((variant, index) => {
            variantsContainer.appendChild(buildVariantRow(variant, index));
        });
        renumberVariants();
    }

    function getCurrentVariantData() {
        return Array.from(variantsContainer.querySelectorAll('.variant-row')).map((row) => ({
            name: row.querySelector('input[name$="[name]"]').value || '',
            price: row.querySelector('input[name$="[price]"]').value || '',
            stock: row.querySelector('input[name$="[stock]"]').value || '',
        }));
    }

    function bindVariantRowEvents(row) {
        const removeBtn = row.querySelector('.remove-variant-btn');
        const imageInputField = row.querySelector('.variant-image-input');
        const imagePreviewField = row.querySelector('.variant-image-preview');
        const placeholder = row.querySelector('.variant-image-placeholder');

        row.querySelectorAll('input[type="text"], input[type="number"]').forEach((input) => {
            input.addEventListener('input', saveDraft);
        });

        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                const index = row.dataset.index;
                variantFiles.delete(index);
                row.remove();
                const currentData = getCurrentVariantData();
                if (currentData.length === 0) {
                    rebuildVariantRows([{ name: '', price: '', stock: '' }]);
                } else {
                    rebuildVariantRows(currentData);
                }
                saveDraft();
            });
        }

        if (imageInputField) {
            imageInputField.addEventListener('change', (event) => {
                const file = event.target.files[0];
                const index = imageInputField.dataset.index;
                
                if (file) {
                    // Store file for form submission
                    variantFiles.set(index, file);
                    
                    const reader = new FileReader();
                    reader.onload = function(loadEvent) {
                        imagePreviewField.src = loadEvent.target.result;
                        imagePreviewField.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        saveDraft();
                    };
                    reader.readAsDataURL(file);
                } else {
                    variantFiles.delete(index);
                    imagePreviewField.src = '';
                    imagePreviewField.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    saveDraft();
                }
            });
        }
    }

    function populateFields(draft) {
        form.querySelector('input[name="title"]').value = draft.title || '';
        form.querySelector('textarea[name="description"]').value = draft.description || '';
        form.querySelector('textarea[name="specification"]').value = draft.specification || '';
        form.querySelector('input[name="location"]').value = draft.location || '';
    }

    function initDraft() {
        const savedDraft = getSavedDraft();
        const hasServerVariants = Array.isArray(serverDraft.variants) && serverDraft.variants.length > 0;
        const variantSource = hasServerVariants ? serverDraft.variants : (savedDraft?.variants || []);

        populateFields({
            title: serverDraft.title ?? savedDraft?.title ?? '',
            description: serverDraft.description ?? savedDraft?.description ?? '',
            specification: serverDraft.specification ?? savedDraft?.specification ?? '',
            location: serverDraft.location ?? savedDraft?.location ?? '',
        });

        rebuildVariantRows(variantSource.length ? variantSource : [{ name: '', price: '', stock: '' }]);
    }

    addVariantBtn.addEventListener('click', function() {
        const currentVariants = getCurrentVariantData();
        currentVariants.push({ name: '', price: '', stock: '' });
        rebuildVariantRows(currentVariants);
        saveDraft();
    });

    form.addEventListener('input', function(event) {
        if (event.target.matches('input[name="title"], textarea[name="description"], textarea[name="specification"], input[name="location"], input[name$="[name]"], input[name$="[price]"], input[name$="[stock]"]')) {
            saveDraft();
        }
    });

    form.addEventListener('submit', function(e) {
        // Ensure all variant indexes are sequential before submission
        renumberVariants();
        clearDraft();
    });

    resetFormBtn.addEventListener('click', function() {
        clearDraft();
        setTimeout(() => {
            rebuildVariantRows([{ name: '', price: '', stock: '' }]);
        }, 0);
    });

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                imagePlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            imagePlaceholder.classList.remove('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', initDraft);
</script>
@endsection
