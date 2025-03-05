{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-[78px] w-full justify-between border border-b border-l-0 border-r-0 border-t-0 px-[60px] max-1180:px-8">
    <!--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    -->
    <!-- Left Nagivation Section -->
    <div class="flex items-center gap-x-10 max-[1180px]:gap-x-5">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

        <a
            href="{{ route('shop.home.index') }}"
            aria-label="@lang('shop::app.components.layouts.header.bagisto')"
        >
            <img
                src="/logo.png"
                width="131"
                height="29"
                alt="{{ config('app.name') }}"
            >
        </a>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

        <v-desktop-category>
            <div class="flex items-center gap-5">
                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>

                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>

                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>
            </div>
        </v-desktop-category>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    <!-- Right Nagivation Section -->
    <div class="flex items-center gap-x-9 max-[1100px]:gap-x-6 max-lg:gap-x-8 {{ !Route::is('shop.home.index') ? '' : 'ml-auto' }}">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        @if(!Route::is('shop.home.index'))
        <!-- Search Bar Container -->
        <div class="relative w-full">
            <form
                action="{{ route('shop.search.index') }}"
                class="flex max-w-[445px] items-center"
                role="search"
                onsubmit="return false;"
            >
                <label
                    for="organic-search"
                    class="sr-only"
                >
                    @lang('shop::app.components.layouts.header.search')
                </label>

                <div class="icon-search pointer-events-none absolute top-2.5 flex items-center text-xl ltr:left-3 rtl:right-3"></div>

                <input
                    type="text"
                    id="search-input"
                    name="query"
                    value="{{ request('query') }}"
                    class="block w-full rounded-lg border border-transparent bg-zinc-100 px-11 py-3 text-xs font-medium text-gray-900 transition-all hover:border-gray-400 focus:border-gray-400"
                    minlength="{{ core()->getConfigData('catalog.products.search.min_query_length') }}"
                    maxlength="{{ core()->getConfigData('catalog.products.search.max_query_length') }}"
                    placeholder="@lang('shop::app.components.layouts.header.search-text')"
                    aria-label="@lang('shop::app.components.layouts.header.search-text')"
                    aria-required="true"
                    pattern="[^\\]+"
                    required
                    oninput="searchProducts()"
                >

                <button
                    type="submit"
                    class="hidden"
                    aria-label="@lang('shop::app.components.layouts.header.submit')"
                >
                </button>

                @if (core()->getConfigData('catalog.products.settings.image_search'))
                    @include('shop::search.images.index')
                @endif
            </form>

            <div id="search-results" class="absolute top-full left-0 right-0 bg-white border rounded-lg mt-1 z-50 hidden">
                <!-- Risultati della ricerca -->
            </div>
        </div>
        @endif

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

        <!-- Right Navigation Links -->
        <div class="mt-1.5 flex gap-x-8 max-[1100px]:gap-x-6 max-lg:gap-x-8">

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before') !!}

            

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.before') !!}

            <!-- Mini cart -->
            @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before') !!}

            
            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.after') !!}
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-desktop-category-template"
    >
        <div
            class="flex items-center gap-5"
            v-if="isLoading"
        >
            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>

            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>

            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>
        </div>

        <div
            class="flex items-center"
            v-else
        >
            <div
                class="group relative flex h-[77px] items-center border-b-4 border-transparent hover:border-b-4 hover:border-navyBlue"
                v-for="category in categories"
            >
                <span>
                    <a
                        :href="category.url"
                        class="inline-block px-5 uppercase"
                    >
                        @{{ category.name }}
                    </a>
                </span>

                <div
                    class="pointer-events-none absolute top-[78px] z-[1] max-h-[580px] w-max max-w-[1260px] translate-y-1 overflow-auto overflow-x-auto border border-b-0 border-l-0 border-r-0 border-t border-[#F3F3F3] bg-white p-9 opacity-0 shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] transition duration-300 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 group-hover:duration-200 group-hover:ease-in ltr:-left-9 rtl:-right-9"
                    v-if="category.children.length"
                >
                    <div class="aigns flex justify-between gap-x-[70px]">
                        <div
                            class="grid w-full min-w-max max-w-[150px] flex-auto grid-cols-[1fr] content-start gap-5"
                            v-for="pairCategoryChildren in pairCategoryChildren(category)"
                        >
                            <template v-for="secondLevelCategory in pairCategoryChildren">
                                <p class="font-medium text-navyBlue">
                                    <a :href="secondLevelCategory.url">
                                        @{{ secondLevelCategory.name }}
                                    </a>
                                </p>

                                <ul
                                    class="grid grid-cols-[1fr] gap-3"
                                    v-if="secondLevelCategory.children.length"
                                >
                                    <li
                                        class="text-sm font-medium text-zinc-500"
                                        v-for="thirdLevelCategory in secondLevelCategory.children"
                                    >
                                        <a :href="thirdLevelCategory.url">
                                            @{{ thirdLevelCategory.name }}
                                        </a>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',

            data() {
                return  {
                    isLoading: true,

                    categories: [],
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.isLoading = false;

                            this.categories = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                pairCategoryChildren(category) {
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }

                        return result;
                    }, []);
                }
            },
        });
    </script>
    <script>
    async function searchProducts() {
        const query = document.getElementById('search-input').value;
        const resultsContainer = document.getElementById('search-results');

        if (query.length < 3) {
            resultsContainer.classList.add('hidden');
            resultsContainer.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`/api/search?q=${query}`);
            const data = await response.json();
            const products = data?.products || [];

            if (products.length > 0) {
                document.getElementById('main').innerHTML = '';
                if (products.length > 0) {
                    document.getElementById('main').innerHTML = `
                        <div class="max-w-[1200px] mx-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
                                ${products.map(product => `
                                    <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition" style="max-width: 250px;">
                                        <a href="/${product.product_url}">
                                            <img src="${product.base_image}" class="w-full h-48 object-cover">
                                            <div class="p-4">
                                                <h3 class="font-medium text-lg mb-2">${product.name}</h3>
                                                <div class="text-gray-600">
                                                    <span class="line-through">${product.formatted_price}</span>
                                                    <span class="text-red-600 ml-2">${product.formatted_price_discounted}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
                resultsContainer.classList.remove('hidden');
                resultsContainer.innerHTML = products.map(product => `
                    <a href="/${product.product_url}" class="block">
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-center">
                                <img src="${product.base_image}" class="w-16 h-16 object-cover rounded">
                                <div class="ml-4" style="margin-left: 2%;">
                                    <div class="font-medium">${product.name}</div>
                                    <div class="text-gray-600">da ${product.formatted_price_discounted} a ${product.formatted_price}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                `).join('');
            } else {
                resultsContainer.classList.add('hidden');
                resultsContainer.innerHTML = '';
            }
        } catch (error) {
            console.error('Search error:', error);
            resultsContainer.classList.add('hidden');
            resultsContainer.innerHTML = '';
        }
    }
</script>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
