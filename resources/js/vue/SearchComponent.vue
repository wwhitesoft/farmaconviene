<template>
    <div class="relative w-full">
        <form @submit.prevent class="flex max-w-[445px] items-center">
            <input
                type="text"
                v-model="query"
                @input="search"
                class="w-full rounded-lg border px-4 py-2"
                placeholder="Cerca prodotti..."
            >
            
            <div v-if="results.length" class="absolute top-full left-0 right-0 bg-white border rounded-lg mt-1 z-50">
                <div v-for="product in results" :key="product.id" class="p-4 hover:bg-gray-50">
                    <div class="flex items-center">
                        <img :src="product.base_image || '/placeholder.jpg'" 
                             class="w-16 h-16 object-cover rounded">
                        <div class="ml-4">
                            <div class="font-medium">{{ product.name }}</div>
                            <div class="text-gray-600">{{ product.formatted_price }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            query: '',
            results: []
        }
    },
    methods: {
        async search() {
            if (this.query.length < 3) {
                this.results = [];
                return;
            }

            try {
                const response = await fetch(`/api/search?q=${this.query}`);
                const data = await response.json();
                this.results = data?.products || [];
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
            }
        }
    }
}
</script>