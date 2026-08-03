<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 hover:underline">Home</a>
                    <span>/</span>
                    <span class="text-gray-700 font-medium">Write a New Post</span>
                </nav>

                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 shrink-0">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </span>
                            Write a New Post
                        </h1>
                        <p class="mt-2 text-gray-600">
                            Share your ideas, tutorials, and insights with the community.
                        </p>
                    </div>

                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition shrink-0">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Posts
                    </a>
                </div>
            </div>

            {{-- Error summary --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms
                     class="mb-8 bg-red-50 border border-red-200 rounded-xl p-5 relative">
                    <button type="button" @click="show = false"
                            class="absolute top-3 right-3 text-red-400 hover:text-red-600 transition p-1">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-red-800 mb-1">Please fix the following before submitting:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('posts.store') }}"
                  x-data="{
                      title: @js(old('title', '')),
                      excerpt: @js(old('excerpt', '')),
                      content: @js(old('content', '')),
                      categoryId: @js(old('category_id') ?? ''),
                      seriesId: @js(old('series_id') ?? ''),
                      status: @js(old('status', 'draft')),
                      selectedTags: @js(array_map('intval', old('tags', []))),
                      allTags: @js($tags->map(fn ($t) => ['id' => $t->id, 'name' => $t->name])),
                      allCategories: @js($categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])),

                      get wordCount() {
                          const t = this.content.trim();
                          return t ? t.split(/\s+/).length : 0;
                      },
                      get readingTime() {
                          return Math.max(1, Math.round(this.wordCount() / 200));
                      },
                      get selectedCategoryName() {
                          const c = this.allCategories.find(c => String(c.id) === String(this.categoryId));
                          return c ? c.name : 'Uncategorized';
                      },
                      toggleTag(id) {
                          const i = this.selectedTags.indexOf(id);
                          if (i === -1) { this.selectedTags.push(id); } else { this.selectedTags.splice(i, 1); }
                      },
                      get readiness() {
                          return [
                              { label: 'Title', done: this.title.trim().length > 0 },
                              { label: 'Category', done: String(this.categoryId).length > 0 },
                              { label: 'Content', done: this.content.trim().length > 0 },
                              { label: 'Tags', done: this.selectedTags.length > 0 },
                          ];
                      },
                      get completedCount() {
                          return this.readiness.filter(c => c.done).length;
                      },
                      get publishLabel() {
                          return this.status === 'draft' ? 'Save Draft'
                               : this.status === 'published' ? 'Publish Post'
                               : 'Archive Post';
                      }
                  }"
                  class="lg:grid lg:grid-cols-5 lg:gap-8">

                @csrf

                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-3 space-y-6">

                    {{-- Title --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                            <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Post Title <span class="text-red-500">*</span>
                            </h2>
                            <span class="text-xs font-medium text-gray-500 tabular-nums" x-text="title.length + ' / 255'"></span>
                        </div>
                        <div class="px-6 py-5">
                            <input type="text" id="title" name="title" maxlength="255" x-model="title"
                                   value="{{ old('title') }}"
                                   placeholder="Enter a compelling title…"
                                   class="w-full text-2xl font-semibold text-gray-900 placeholder-gray-300 border-0 border-b-2 border-gray-200 focus:border-indigo-500 focus:ring-0 px-0 py-2 transition-colors">
                            <div class="mt-3 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 transition-all duration-300 rounded-full"
                                     :style="'width: ' + Math.min(100, (title.length / 255) * 100) + '%'"></div>
                            </div>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </section>

                    {{-- Content --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                            <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Content <span class="text-red-500">*</span>
                            </h2>
                            <span class="text-xs font-medium text-gray-500 tabular-nums" x-text="wordCount + ' words · ' + readingTime + ' min read'"></span>
                        </div>
                        <div class="px-6 py-5">
                            <textarea id="content" name="content" x-model="content" rows="14"
                                      placeholder="Write your story… Use blank lines to separate paragraphs."
                                      class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 leading-relaxed resize-y">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-400 flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Plain text is supported. Paragraphs separated by blank lines are preserved.
                            </p>
                        </div>
                    </section>

                    {{-- Details: Category & Series --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2 mb-4">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            Details
                        </h2>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="category_id" name="category_id" x-model="categoryId"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none pr-10 bg-white">
                                        <option value="">Select category…</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </span>
                                </div>
                                @error('category_id')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="series_id" class="block text-sm font-medium text-gray-700 mb-1.5">Series (optional)</label>
                                <div class="relative">
                                    <select id="series_id" name="series_id" x-model="seriesId"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none pr-10 bg-white">
                                        <option value="">No series</option>
                                        @foreach ($series as $s)
                                            <option value="{{ $s->id }}">{{ $s->title }}</option>
                                        @endforeach
                                    </select>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </span>
                                </div>
                                @error('series_id')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    {{-- Excerpt --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1.5">Excerpt (optional)</label>
                        <input type="text" id="excerpt" name="excerpt" maxlength="500" x-model="excerpt"
                               value="{{ old('excerpt') }}"
                               placeholder="A short summary shown on the listing page…"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="mt-1.5 flex items-center justify-between gap-4">
                            <p class="text-xs text-gray-400">
                                Shown on the home page and in search results.
                            </p>
                            <span class="text-xs font-medium text-gray-500 tabular-nums shrink-0" x-text="excerpt.length + ' / 500'"></span>
                        </div>
                        @error('excerpt')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </section>

                    {{-- Tags --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2 mb-1">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.995 1.995 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Tags
                        </h2>
                        <p class="text-sm text-gray-500 mb-4">Select tags to help readers discover your post.</p>

                        <div class="flex flex-wrap gap-2">
                            <template x-for="tag in allTags" :key="tag.id">
                                <button type="button"
                                        @click="toggleTag(tag.id)"
                                        :class="selectedTags.includes(tag.id)
                                            ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                                            : 'bg-white text-gray-600 border-gray-300 hover:border-indigo-400 hover:text-indigo-600'"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border text-sm font-medium transition select-none">
                                    <span class="h-1.5 w-1.5 rounded-full shrink-0"
                                          :class="selectedTags.includes(tag.id) ? 'bg-white' : 'bg-gray-300'"></span>
                                    <span x-text="tag.name"></span>
                                </button>
                            </template>
                        </div>

                        <template x-for="tagId in selectedTags" :key="tagId">
                            <input type="hidden" name="tags[]" :value="tagId">
                        </template>

                        <p class="mt-2 text-xs text-gray-400" x-show="selectedTags.length > 5">
                            You can select at most 5 tags.
                        </p>
                        @error('tags')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </section>

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="lg:col-span-2 mt-6 lg:mt-0">
                    <div class="space-y-6 lg:sticky lg:top-6">

                        {{-- Publish card --}}
                        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-1">
                                <h2 class="font-semibold text-gray-900">Publish</h2>
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full transition"
                                      :class="status === 'draft' ? 'bg-gray-100 text-gray-600'
                                            : status === 'published' ? 'bg-green-100 text-green-700'
                                            : 'bg-amber-100 text-amber-700'"
                                      x-text="status.charAt(0).toUpperCase() + status.slice(1)"></span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Choose where this post sits in its lifecycle.</p>

                            <div class="grid grid-cols-3 gap-1 p-1 bg-gray-100 rounded-xl mb-5">
                                <button type="button" @click="status = 'draft'"
                                        :class="status === 'draft' ? 'bg-white shadow text-gray-800' : 'text-gray-500 hover:text-gray-700'"
                                        class="py-1.5 rounded-lg text-sm font-medium transition">Draft</button>
                                <button type="button" @click="status = 'published'"
                                        :class="status === 'published' ? 'bg-white shadow text-green-700' : 'text-gray-500 hover:text-gray-700'"
                                        class="py-1.5 rounded-lg text-sm font-medium transition">Published</button>
                                <button type="button" @click="status = 'archived'"
                                        :class="status === 'archived' ? 'bg-white shadow text-amber-700' : 'text-gray-500 hover:text-gray-700'"
                                        class="py-1.5 rounded-lg text-sm font-medium transition">Archived</button>
                            </div>

                            <input type="hidden" name="status" :value="status">

                            <button type="submit"
                                    :class="status === 'draft' ? 'bg-gray-700 hover:bg-gray-800'
                                          : status === 'published' ? 'bg-indigo-600 hover:bg-indigo-700'
                                          : 'bg-amber-600 hover:bg-amber-700'"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-white font-medium transition shadow-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="publishLabel"></span>
                            </button>

                            <p class="mt-2 text-xs text-gray-400 text-center"
                               x-text="status === 'draft' ? 'Drafts are only visible to you.'
                                     : status === 'published' ? 'This post will be publicly visible.'
                                     : 'Archived posts are hidden from the public feed.'"></p>

                            <a href="{{ route('home') }}"
                               class="mt-3 w-full inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                Cancel
                            </a>

                            {{-- Readiness checklist --}}
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-medium text-gray-700">Post readiness</p>
                                    <span class="text-xs font-semibold text-indigo-600 tabular-nums" x-text="completedCount + ' / 4'"></span>
                                </div>
                                <ul class="space-y-2.5">
                                    <template x-for="item in readiness" :key="item.label">
                                        <li class="flex items-center gap-2.5 text-sm">
                                            <span class="inline-flex items-center justify-center h-5 w-5 rounded-full transition"
                                                  :class="item.done ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'">
                                                <svg x-show="item.done" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <svg x-show="!item.done" class="h-1.5 w-1.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="6"/>
                                                </svg>
                                            </span>
                                            <span :class="item.done ? 'text-gray-700' : 'text-gray-400'" x-text="item.label"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </section>

                        {{-- Live preview --}}
                        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Live Preview
                                </h2>
                            </div>
                            <div class="px-6 py-5">
                                <p class="text-xs text-gray-400 mb-3">This is how your post will look to readers.</p>

                                <article class="border border-gray-100 rounded-xl bg-gray-50 p-5">
                                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3 flex-wrap">
                                        <span class="text-indigo-600 font-medium" x-text="selectedCategoryName"></span>
                                        <span>·</span>
                                        <span x-text="readingTime + ' min read'"></span>
                                        <span>·</span>
                                        <span x-text="wordCount + ' words'"></span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 break-words"
                                        x-text="title || 'Your post title will appear here'"></h3>
                                    <p class="text-sm text-gray-600 mb-4 break-words"
                                       x-text="excerpt || 'Your excerpt will appear here…'"></p>
                                    <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap break-words max-h-56 overflow-y-auto"
                                         x-text="content || 'Start typing to see a preview of your post content.'"></div>
                                </article>
                            </div>
                        </section>

                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>