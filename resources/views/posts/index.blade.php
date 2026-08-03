<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Latest Posts
                </h2>

                @can('create', App\Models\Post::class)
                    <a href="{{ route('posts.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition shrink-0">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Post
                    </a>
                @endcan
            </div>

            @if ($posts->isEmpty())
                <div class="bg-white p-8 rounded-lg shadow text-center text-gray-500">
                    No posts published yet.
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($posts as $post)
                        <article class="bg-white p-6 rounded-lg shadow">
                            <div class="text-sm text-gray-500 mb-2">
                                {{ $post->category->name }}
                                &middot;
                                {{ $post->published_at->format('M d, Y') }}
                                &middot;
                                {{ $post->reading_time }} min read
                            </div>

                            <h3 class="text-xl font-bold mb-2">
                                <a href="{{ route('posts.show', $post) }}" class="text-gray-900 hover:text-indigo-600">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            @if ($post->excerpt)
                                <p class="text-gray-600 mb-3">{{ $post->excerpt }}</p>
                            @endif

                            <div class="text-sm text-gray-500">
                                by {{ $post->user->name }}
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>