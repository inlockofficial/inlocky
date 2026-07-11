@php
    $formHeading = $heading ?? 'Request a Price Estimate';
    $formDescription = $description ?? null;
@endphp

@if ($errors->any())
    <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
        {{ $errors->first() }}
    </div>
@endif

@if ($formHeading)
    <h3 class="text-xl font-medium mb-2 text-white">{{ $formHeading }}</h3>
@endif

@if ($formDescription)
    <p class="text-sm text-gray-400 mb-6">{{ $formDescription }}</p>
@elseif ($formHeading)
    <div class="mb-6"></div>
@endif

<form action="{{ route('request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1.5">AliExpress Product Link <span class="text-[#e9c38c]">*</span></label>
        <input type="url" name="ali_link" placeholder="https://aliexpress.com/item/..." required
            value="{{ old('ali_link') }}"
            class="w-full bg-[#0f1115] border border-[#242833] rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:border-[#e9c38c] focus:ring-1 focus:ring-[#e9c38c] transition-all outline-none">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Color <span class="text-gray-500 text-xs font-normal">(Optional)</span></label>
            <select name="color" class="w-full bg-[#0f1115] border border-[#242833] rounded-xl px-4 py-3 text-white focus:border-[#e9c38c] focus:ring-1 focus:ring-[#e9c38c] transition-all outline-none appearance-none">
                <option value="">Select color</option>
                @foreach (['Black', 'White', 'Blue', 'Red', 'Green', 'Pink', 'Other'] as $colorOption)
                    <option value="{{ $colorOption }}" @selected(old('color') === $colorOption)>{{ $colorOption }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Size <span class="text-gray-500 text-xs font-normal">(Optional)</span></label>
            <select name="size" class="w-full bg-[#0f1115] border border-[#242833] rounded-xl px-4 py-3 text-white focus:border-[#e9c38c] focus:ring-1 focus:ring-[#e9c38c] transition-all outline-none appearance-none">
                <option value="">Select size</option>
                @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Other'] as $sizeOption)
                    <option value="{{ $sizeOption }}" @selected(old('size') === $sizeOption)>{{ $sizeOption }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Quantity <span class="text-[#e9c38c]">*</span></label>
            <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required
                class="w-full bg-[#0f1115] border border-[#242833] rounded-xl px-4 py-3 text-white focus:border-[#e9c38c] focus:ring-1 focus:ring-[#e9c38c] transition-all outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Gender <span class="text-gray-500 text-xs font-normal">(Optional)</span></label>
            <select name="gender" class="w-full bg-[#0f1115] border border-[#242833] rounded-xl px-4 py-3 text-white focus:border-[#e9c38c] focus:ring-1 focus:ring-[#e9c38c] transition-all outline-none appearance-none">
                <option value="">Not specified</option>
                <option value="male" @selected(old('gender') === 'male')>Men</option>
                <option value="female" @selected(old('gender') === 'female')>Women</option>
                <option value="unisex" @selected(old('gender') === 'unisex')>Unisex</option>
                <option value="kids" @selected(old('gender') === 'kids')>Kids</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1.5">Add Screenshot <span class="text-gray-500 text-xs font-normal">(Optional)</span></label>
        <input type="file" name="screenshot" accept="image/*"
            class="w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#242833] file:text-white hover:file:bg-[#2a2f3a] transition-all">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1.5">Extra Details</label>
        <textarea name="custom_note" rows="2" placeholder="Example: size XXL, dark blue color, cotton version..."
            class="w-full bg-[#0f1115] border border-[#242833] rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:border-[#e9c38c] focus:ring-1 focus:ring-[#e9c38c] transition-all outline-none resize-none">{{ old('custom_note') }}</textarea>
    </div>

    <button type="submit" class="w-full py-3.5 bg-[#e9c38c] text-black font-semibold rounded-xl hover:bg-[#d6b07a] transition-colors mt-2">
        Request DZD Price
    </button>
</form>
