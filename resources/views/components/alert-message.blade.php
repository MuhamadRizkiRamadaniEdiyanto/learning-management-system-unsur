@if ($message = Session::get('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-green-800">{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-red-800">{{ $message }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <ul class="text-red-800">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
