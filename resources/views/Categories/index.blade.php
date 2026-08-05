<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Categories
                </h2>
            </div>

            @if ($categories->isEmpty())
                <div class="bg-white p-8 rounded-lg shadow text-center text-gray-500">
                    No categories found.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($categories as $category)
                        <a href="{{ route('categories.show', $category) }}" class="block bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                            <div class="font-semibold text-lg text-gray-900">{{ $category->name }}</div>
                            <div class="text-sm text-gray-500">{{ $category->posts_count }} posts</div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
