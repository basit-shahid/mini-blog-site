<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                Latest Posts
            </h2>

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
