<!-- Mini Cart Vue Component -->
<v-mini-cart>
    <a href="https://farmaconviene.com/checkout/cart">
        <span
            class="icon-cart cursor-pointer text-2xl"
            role="button"
            aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
        ></span>
    </a>
</v-mini-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mini-cart-template"
    >
        <a href="https://farmaconviene.com/checkout/cart">
            <span class="relative">
                <span
                    class="icon-cart cursor-pointer text-2xl"
                    role="button"
                    aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                    tabindex="0"
                ></span>

                <span
                    class="absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white max-md:px-2 max-md:py-1.5 ltr:left-5 max-md:ltr:left-4 rtl:right-5 max-md:rtl:right-4"
                    v-if="cart?.items_qty"
                >
                    @{{ cart.items_qty }}
                </span>
            </span>
        </a>
    </script>

    <script type="module">
        app.component("v-mini-cart", {
            template: '#v-mini-cart-template',

            data() {
                return  {
                    cart: null,
                }
            },

            mounted() {
                this.getCart();
                
                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });
            },

            methods: {
                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
                },
            },
        });
    </script>
@endpushOnce
