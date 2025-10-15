@extends(get_user_layout())

@section('title', $title ?? 'Административная панель')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $title ?? 'Административная панель' }}</h1>
        <p class="mt-1 text-sm text-gray-600">{{ $description ?? 'Управление разделом' }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600">Раздел находится в разработке...</p>
    </div>
</div>
@endsection