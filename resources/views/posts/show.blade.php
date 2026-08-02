<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="bg-white p-8 rounded-lg shadow">
                <div class="text-sm text-gray-500 mb-4">
                    {{ $post->category->name }}
                    &middot;
                    {{ $post->published_at->format('M d, Y') }}
                    &middot;
                    {{ $post->reading_time }} min read
                    &middot;
                    by {{ $post->user->name }}
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $post->title }}</h1>

                <div class="prose max-w-none text-gray-700 whitespace-pre-line">
                    {{ $post->content }}
                </div>

                @if ($post->tags->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($post->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <div class="mt-6">
                <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    &larr; Back to all posts
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
