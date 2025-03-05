<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.cart.index.cart')"/>

    <meta name="keywords" content="@lang('shop::app.checkout.cart.index.cart')"/>
@endPush

<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.checkout.cart.index.cart')
    </x-slot>

    {!! view_render_event('bagisto.shop.checkout.cart.header.before') !!}

    {!! view_render_event('bagisto.shop.checkout.cart.header.after') !!}

    <div class="flex-auto">
        <div class="container px-[60px] max-lg:px-8 max-md:px-4">
            
            {!! view_render_event('bagisto.shop.checkout.cart.breadcrumbs.before') !!}

            <!-- Breadcrumbs -->
            @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
                <x-shop::breadcrumbs name="cart" />
            @endif

            {!! view_render_event('bagisto.shop.checkout.cart.breadcrumbs.after') !!}

            @php
                $errors = Cart::getErrors();
            @endphp
            
            @if (! empty($errors) && $errors['error_code'] === 'MINIMUM_ORDER_AMOUNT')
                <div class="mt-5 w-full gap-12 rounded-lg bg-[#FFF3CD] px-5 py-3 text-[#383D41] max-sm:px-3 max-sm:py-2 max-sm:text-sm">
                    {{ $errors['message'] }}: {{ $errors['amount'] }}
                </div>
            @endif

            <v-cart ref="vCart">
                <!-- Cart Shimmer Effect -->
                <x-shop::shimmer.checkout.cart :count="3" />
            </v-cart>
        </div>
    </div>

    @if (core()->getConfigData('sales.checkout.shopping_cart.cross_sell'))
        {!! view_render_event('bagisto.shop.checkout.cart.cross_sell_carousel.before') !!}

        <!-- Cross-sell Product Carousal -->
        <x-shop::products.carousel
            :title="trans('shop::app.checkout.cart.index.cross-sell.title')"
            :src="route('shop.api.checkout.cart.cross-sell.index')"
        >
        </x-shop::products.carousel>

        {!! view_render_event('bagisto.shop.checkout.cart.cross_sell_carousel.after') !!}
    @endif    

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-cart-template"
        >
            <div>
                <!-- Cart Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.checkout.cart :count="3" />
                </template>

                <!-- Cart Information -->
                <template v-else>
                    <div 
                        class="mt-8 flex flex-wrap gap-20 pb-8 max-1060:flex-col max-md:mt-0 max-md:gap-[30px] max-md:pb-0"
                        v-if="cart?.items?.length"
                    >
                        <div class="flex flex-1 flex-col gap-6 max-md:gap-5">

                            {!! view_render_event('bagisto.shop.checkout.cart.cart_mass_actions.before') !!}

                            <!-- Cart Mass Action Container -->
                            <div class="flex items-center justify-between border-b border-zinc-200 pb-2.5 max-md:py-2.5">
                                <div class="flex select-none items-center">
                                    <input
                                        type="checkbox"
                                        id="select-all"
                                        class="peer hidden"
                                        v-model="allSelected"
                                        @change="selectAll"
                                    >

                                    <label
                                        class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue"
                                        for="select-all"
                                        tabindex="0"
                                        aria-label="@lang('shop::app.checkout.cart.index.select-all')"
                                        aria-labelledby="select-all-label"
                                    >
                                    </label>

                                    <span
                                        class="text-xl max-sm:text-sm ltr:ml-2.5 rtl:mr-2.5"
                                        role="heading"
                                        aria-level="2"
                                    >
                                        @{{ "@lang('shop::app.checkout.cart.index.items-selected')".replace(':count', selectedItemsCount) }}
                                    </span>
                                </div>

                                <div v-if="selectedItemsCount">
                                    <span
                                        class="cursor-pointer text-base text-blue-700 max-sm:text-xs" 
                                        role="button"
                                        tabindex="0"
                                        @click="removeSelectedItems"
                                    >
                                        @lang('shop::app.checkout.cart.index.remove')
                                    </span>

                                    @if (auth()->guard()->check())
                                        <span class="mx-2.5 border-r-2 border-zinc-200"></span>

                                        <span
                                            class="cursor-pointer text-base text-blue-700 max-sm:text-xs"
                                            role="button"
                                            tabindex="0"
                                            @click="moveToWishlistSelectedItems"
                                        >
                                            @lang('shop::app.checkout.cart.index.move-to-wishlist')
                                        </span>    
                                    @endif
                                </div>
                            </div>
                        
                            {!! view_render_event('bagisto.shop.checkout.cart.cart_mass_actions.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.cart.item.listing.before') !!}

                            <!-- Cart Item Listing Container -->
                            <div 
                                class="grid gap-y-6" 
                                v-for="item in cart?.items"
                            >
                                <div class="flex justify-between gap-x-2.5 border-b border-zinc-200 pb-5">
                                    <div class="flex gap-x-5">
                                        <div class="mt-11 select-none max-md:mt-9 max-sm:mt-7">
                                            <input
                                                type="checkbox"
                                                :id="'item_' + item.id"
                                                class="peer hidden"
                                                v-model="item.selected"
                                                @change="updateAllSelected"
                                            >

                                            <label
                                                class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue"
                                                :for="'item_' + item.id"
                                                tabindex="0"
                                                aria-label="@lang('shop::app.checkout.cart.index.select-cart-item')"
                                                aria-labelledby="select-item-label"
                                            ></label>
                                        </div>

                                        {!! view_render_event('bagisto.shop.checkout.cart.item_image.before') !!}

                                        <!-- Cart Item Image -->
                                        <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`">
                                            <x-shop::media.images.lazy
                                                class="h-[110px] max-w-[110px] rounded-xl max-md:h-20 max-md:max-w-20"
                                                ::src="item.base_image.small_image_url"
                                                ::alt="item.name"
                                                width="110"
                                                height="110"
                                                ::key="item.id"
                                                ::index="item.id"
                                            />
                                        </a>

                                        {!! view_render_event('bagisto.shop.checkout.cart.item_image.after') !!}

                                        <!-- Cart Item Options Container -->
                                        <div class="grid place-content-start gap-y-2.5 max-md:gap-y-0">
                                            {!! view_render_event('bagisto.shop.checkout.cart.item_name.before') !!}

                                            <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`">
                                                <p class="text-base font-medium max-sm:text-sm">
                                                    @{{ item.name }}
                                                </p>
                                            </a>

                                            {!! view_render_event('bagisto.shop.checkout.cart.item_name.after') !!}

                                            {!! view_render_event('bagisto.shop.checkout.cart.item_details.before') !!}

                                            <!-- Cart Item Options Container -->
                                            <div
                                                class="grid select-none gap-x-2.5 gap-y-1.5"
                                                v-if="item.options.length"
                                            >
                                                <!-- Details Toggler -->
                                                <div class="">
                                                    <p
                                                        class="flex cursor-pointer items-center gap-x-4 text-base max-md:gap-x-1.5 max-sm:text-xs"
                                                        @click="item.option_show = ! item.option_show"
                                                    >
                                                        @lang('shop::app.checkout.cart.index.see-details')

                                                        <span
                                                            class="text-2xl max-md:text-lg"
                                                            :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                                        ></span>
                                                    </p>
                                                </div>

                                                <!-- Option Details -->
                                                <div
                                                    class="grid gap-2"
                                                    v-show="item.option_show"
                                                >
                                                    <template v-for="option in item.options">
                                                        <div class="max-md:grid max-md:gap-0.5">
                                                            <p class="text-sm font-medium text-zinc-500 max-md:font-normal max-sm:text-xs">
                                                                @{{ option.attribute_name + ':' }}
                                                            </p>

                                                            <p class="text-sm max-sm:text-xs">
                                                                @{{ option.option_label }}
                                                            </p>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            {!! view_render_event('bagisto.shop.checkout.cart.item_details.after') !!}

                                            {!! view_render_event('bagisto.shop.checkout.cart.formatted_total.before') !!}

                                            <div class="md:hidden">
                                                <p class="text-lg font-semibold max-md:text-sm">
                                                    @{{ item.formatted_total }}
                                                </p>
                                                
                                                <span
                                                    class="cursor-pointer text-base text-blue-700 max-md:hidden"
                                                    role="button"
                                                    tabindex="0"
                                                    @click="removeItem(item.id)"
                                                >
                                                    @lang('shop::app.checkout.cart.index.remove')
                                                </span>
                                            </div>

                                            {!! view_render_event('bagisto.shop.checkout.cart.formatted_total.after') !!}

                                            {!! view_render_event('bagisto.shop.checkout.cart.quantity_changer.before') !!}

                                            <div class="flex items-center gap-2.5 max-md:mt-2.5">
                                                <x-shop::quantity-changer
                                                    class="flex max-w-max items-center gap-x-2.5 rounded-[54px] border border-navyBlue px-3.5 py-1.5 max-md:gap-x-1.5 max-md:px-1 max-md:py-0.5"
                                                    name="quantity"
                                                    ::value="item?.quantity"
                                                    @change="setItemQuantity(item.id, $event)"
                                                />

                                                <!-- For Mobile view Remove Button -->
                                                <span
                                                    class="hidden cursor-pointer text-sm text-blue-700 max-md:block"
                                                    role="button"
                                                    tabindex="0"
                                                    @click="removeItem(item.id)"
                                                >
                                                    @lang('shop::app.checkout.cart.index.remove')
                                                </span>
                                            </div>

                                            {!! view_render_event('bagisto.shop.checkout.cart.quantity_changer.after') !!}
                                        </div>
                                    </div>

                                    <div class="text-right max-md:hidden">
                                        {!! view_render_event('bagisto.shop.checkout.cart.total.before') !!}
                                        
                                        <template v-if="displayTax.prices == 'including_tax'">
                                            <p class="text-lg font-semibold">
                                                @{{ item.formatted_total_incl_tax }}
                                            </p>
                                        </template>

                                        <template v-else-if="displayTax.prices == 'both'">
                                            <p class="flex flex-col text-lg font-semibold">
                                                @{{ item.formatted_total_incl_tax }}

                                                <span class="text-xs font-normal">
                                                    @lang('shop::app.checkout.cart.index.excl-tax')
                                                    
                                                    <span class="font-medium">@{{ item.formatted_total }}</span>
                                                </span>
                                            </p>
                                        </template>

                                        <template v-else>
                                            <p class="text-lg font-semibold">
                                                @{{ item.formatted_total }}
                                            </p>
                                        </template>
                                        
    
                                        {!! view_render_event('bagisto.shop.checkout.cart.total.after') !!}

                                        {!! view_render_event('bagisto.shop.checkout.cart.remove_button.before') !!}
                                        
                                        <!-- Cart Item Remove Button -->
                                        <span
                                            class="cursor-pointer text-base text-blue-700" 
                                            role="button"
                                            tabindex="0"
                                            @click="removeItem(item.id)"
                                        >
                                            @lang('shop::app.checkout.cart.index.remove')
                                        </span>
                                        
                                        {!! view_render_event('bagisto.shop.checkout.cart.remove_button.after') !!}
                                        
                                    </div>
                                   
                                </div>
                                
                            </div>
                            

                            {!! view_render_event('bagisto.shop.checkout.cart.item.listing.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.cart.controls.before') !!}
        
                            <!-- Cart Item Actions -->
                            <div class="flex flex-wrap justify-end gap-8 max-md:justify-between max-md:gap-5">
                                {!! view_render_event('bagisto.shop.checkout.cart.continue_shopping.before') !!}

                                <a
                                    class="secondary-button max-h-14 rounded-2xl max-md:rounded-lg max-md:px-6 max-md:py-3 max-md:text-sm max-sm:py-2"
                                    href="{{ route('shop.home.index') }}"
                                >
                                    @lang('shop::app.checkout.cart.index.continue-shopping')
                                </a> 

                                {!! view_render_event('bagisto.shop.checkout.cart.continue_shopping.after') !!}

                                {!! view_render_event('bagisto.shop.checkout.cart.update_cart.before') !!}

                                <x-shop::button
                                    class="secondary-button max-h-14 rounded-2xl max-md:rounded-lg max-md:px-6 max-md:py-3 max-md:text-sm max-sm:py-2"
                                    :title="trans('shop::app.checkout.cart.index.update-cart')"
                                    ::loading="isStoring"
                                    ::disabled="isStoring"
                                    @click="update()"
                                />

                                {!! view_render_event('bagisto.shop.checkout.cart.update_cart.after') !!}
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.controls.after') !!}
                            @include('shop::checkout.cart.summary')
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.cart.summary.before') !!}
                         <!-- Tabella di comparazione prezzi -->
                         <div v-if="savings && savings.length > 0" class="border-b border-zinc-200 p-4 mb-4 mt-[10%]">
                                <p class="text-sm font-medium text-zinc-500 mb-4">
                                    Confronto prezzi con altri siti:
                                </p>
                                
                                <table class="w-full table-fixed border-collapse">
                                    <thead>
                                        <tr class="border-b bg-gray-100">
                                            <th class="py-3 px-4 text-left w-1/3">Farmacia</th>
                                            <th class="py-3 px-4 text-center w-1/3">Prezzo</th>
                                            <th class="py-3 px-4 text-center w-1/3">Differenza</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr 
                                            v-for="site in savings" 
                                            :key="site.source_table"
                                            class="border-b hover:bg-gray-50"
                                        >
                                            <td class="py-3 px-4 text-left whitespace-nowrap">@{{ site.source_table }}</td>
                                            <td class="py-3 px-4 text-center font-medium">
                                                <span v-if="isValidPrice(site.total_price)">@{{ formatCurrency(site.total_price) }}</span>
                                                <span v-else>-</span>
                                            </td>
                                            <td 
                                                class="py-3 px-4 text-center transition-colors duration-200"
                                                :class="{
                                                    'bg-red-50': isValidDifference(site.total_price) && calculateDifference(site.total_price) > 0,
                                                    'bg-green-50': isValidDifference(site.total_price) && calculateDifference(site.total_price) < 0
                                                }"
                                            >
                                                <span 
                                                    v-if="isValidDifference(site.total_price)"
                                                    :class="{
                                                        'text-red-600': calculateDifference(site.total_price) > 0,
                                                        'text-green-600': calculateDifference(site.total_price) < 0
                                                    }"
                                                    class="text-sm"
                                                >
                                                    @{{ formatCurrency(calculateDifference(site.total_price)) }} 
                                                    (@{{ calculatePercentage(site.total_price) }}%)
                                                </span>
                                                <span v-else class="text-sm">-</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Fine tabella di comparazione prezzi -->
                        <!-- Cart Summary Blade File -->
                       
                        
                        {!! view_render_event('bagisto.shop.checkout.cart.summary.after') !!}
                        
                        
                    </div>

                    <!-- Empty Cart Section -->
                    <div
                        class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center"
                        v-else
                    >
                        <img
                            class="max-md:h-[100px] max-md:w-[100px]"
                            src="{{ bagisto_asset('images/thank-you.png') }}"
                            alt="@lang('shop::app.checkout.cart.index.empty-product')"
                        />
                        
                        <p
                            class="text-xl max-md:text-sm"
                            role="heading"
                        >
                            @lang('shop::app.checkout.cart.index.empty-product')
                        </p>
                    </div>
                </template>
            </div>
        </script>

        <script type="module">
            app.component("v-cart", {
                template: '#v-cart-template',

                data() {
                    return  {
                        cart: [],
                        savings: null,

                        allSelected: false,

                        applied: {
                            quantity: {},
                        },

                        displayTax: {
                            prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",

                            subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",
                            
                            shipping: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_shipping_amount') }}",
                        },

                        isLoading: true,

                        isStoring: false,

                    }
                },

                mounted() {
                    this.getCart();
                },

                computed: {
                    selectedItemsCount() {
                        return this.cart?.items?.filter(item => item.selected)?.length || 0;
                    },
                },

                methods: {
                    calculateSavings() {
                        if (!this.cart?.items?.length) {
                            this.savings = null;
                            return;
                        }

                        // Ottieni gli SKU e le quantità
                        const items = this.cart.items.map(item => ({
                            sku: item.sku,
                            quantity: this.applied.quantity[item.id] || item.quantity
                        }));

                        this.$axios.post('{{ route('shop.api.calculate.savings') }}', {
                            items: items
                        })
                        .then(response => {
                            this.savings = response.data.savings;
                        })
                        .catch(error => {
                            console.error('Error calculating savings:', error);
                            this.savings = null;
                        });
                    },

                    isValidPrice(price) {
                        return price !== null && !isNaN(parseFloat(price)) && parseFloat(price) > 0;
                    },

                    formatCurrency(value) {
                        if (value === null || isNaN(parseFloat(value))) return '-';
                        return parseFloat(value).toFixed(2) + '€';
                    },

                    isValidDifference(sitePrice) {
                        if (!this.isValidPrice(sitePrice)) return false;
                        if (!this.cart?.grand_total) return false;
                        
                        const cartTotal = parseFloat(this.cart.grand_total);
                        return !isNaN(cartTotal) && cartTotal > 0;
                    },

                    calculateDifference(sitePrice) {
                        if (!this.isValidDifference(sitePrice)) return null;
                        
                        const cartTotal = parseFloat(this.cart.grand_total);
                        const price = parseFloat(sitePrice);
                        return (price - cartTotal).toFixed(2);
                    },

                    calculatePercentage(sitePrice) {
                        if (!this.isValidDifference(sitePrice)) return null;
                        
                        const cartTotal = parseFloat(this.cart.grand_total);
                        if (cartTotal === 0) return '0';
                        
                        const price = parseFloat(sitePrice);
                        const difference = price - cartTotal;
                        return ((difference / cartTotal) * 100).toFixed(2);
                    },

                    getCart() {
                        this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                            .then(response => {
                                this.cart = response.data.data;
                                this.calculateSavings();
                                this.isLoading = false;

                                if (response.data.message) {
                                    this.$emitter.emit('add-flash', { type: 'info', message: response.data.message });
                                }
                            })
                            .catch(error => {});
                    },

                    setCart(cart) {
                        this.cart = cart;
                    },

                    selectAll() {
                        if (!this.cart?.items) return;
                        
                        for (let item of this.cart.items) {
                            item.selected = this.allSelected;
                        }
                    },

                    updateAllSelected() {
                        if (!this.cart?.items?.length) return;
                        
                        this.allSelected = this.cart.items.every(item => item.selected);
                    },

                    update() {
                        this.isStoring = true;

                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty: this.applied.quantity })
                            .then(response => {
                                this.cart = response.data.data;
                                
                                // Ricalcola i risparmi dopo l'aggiornamento
                                this.$nextTick(() => {
                                    this.calculateSavings();
                                });

                                if (response.data.message) {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isStoring = false;
                            })
                            .catch(error => {
                                this.isStoring = false;
                            });
                    },

                    setItemQuantity(itemId, quantity) {
                        this.applied.quantity[itemId] = quantity;
                        this.update();
                    },

                    removeItem(itemId) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                        '_method': 'DELETE',
                                        'cart_item_id': itemId,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;
                                        
                                        // Ricalcola i risparmi dopo la rimozione
                                        this.$nextTick(() => {
                                            this.calculateSavings();
                                        });

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    removeSelectedItems() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                                this.$axios.post('{{ route('shop.api.checkout.cart.destroy_selected') }}', {
                                        '_method': 'DELETE',
                                        'ids': selectedItemsIds,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;
                                        
                                        // Ricalcola i risparmi dopo la rimozione
                                        this.$nextTick(() => {
                                            this.calculateSavings();
                                        });

                                        this.$emitter.emit('update-mini-cart', response.data.data );

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    moveToWishlistSelectedItems() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                                const selectedItemsQty = this.cart.items.filter(item => item.selected).map(item => this.applied.quantity[item.id] ?? item.quantity);

                                this.$axios.post('{{ route('shop.api.checkout.cart.move_to_wishlist') }}', {
                                        'ids': selectedItemsIds,
                                        'qty': selectedItemsQty
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;
                                        
                                        // Ricalcola i risparmi dopo lo spostamento
                                        this.$nextTick(() => {
                                            this.calculateSavings();
                                        });

                                        this.$emitter.emit('update-mini-cart', response.data.data );

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            }
                        });
                    }
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts>