import { createApp } from 'vue';
import SearchComponent from './components/SearchComponent.vue';

const app = createApp({});
app.component('search-component', SearchComponent);

app.mount('#app');