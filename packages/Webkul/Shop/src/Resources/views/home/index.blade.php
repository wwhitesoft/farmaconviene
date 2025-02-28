@php
    $channel = core()->getCurrentChannel();
@endphp

@push('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endpush

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            line-height: 1.6;
        }

        .hero {
            background: linear-gradient(135deg, #64d9aa 0%, #3fa780 100%);
            color: #fff;
            text-align: center;
            padding: 60px 20px;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-container {
            width: 100%;
            margin: 0 auto;
            position: relative;
        }

        .search-container input {
            width: 100%;
            padding: 20px 60px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            outline: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .search-container i {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #64d9aa;
            font-size: 1.2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .icons-section {
            padding: 80px 0;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
        }

        .col-4 {
            flex: 0 0 33.333333%;
            padding: 15px;
        }

        .icon-item {
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .icon-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(100, 217, 170, 0.1);
        }

        .icon-item i {
            font-size: 3rem;
            color: #64d9aa;
            margin-bottom: 20px;
        }

        .icon-item h5 {
            color: #333;
            font-size: 1.1rem;
            margin-top: 15px;
        }

        .graph-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }

        .graph-placeholder {
            background: #e9ecef;
            height: 300px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            margin: 30px 0;
        }

        .info-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .info-section h3 {
            color: #333;
            margin-bottom: 40px;
            font-size: 2rem;
        }

        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }

        .footer p {
            opacity: 0.8;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .col-4 {
                flex: 0 0 100%;
            }
            
            .hero h1 {
                font-size: 1.8rem;
            }
        }
        * {
            font-family: 'Poppins', sans-serif;
        }

        .hero {
            background: linear-gradient(135deg, #64d9aa 0%, #3fa780 100%);
            color: #fff;
            text-align: center;
            padding: 60px 20px;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .search-container input {
            width: 100%;
            padding: 20px 60px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            outline: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .search-container i {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #64d9aa;
            font-size: 1.2rem;
        }

        .icons-section {
            padding: 80px 0;
        }

        .icon-item {
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .icon-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(100, 217, 170, 0.1);
        }

        .icon-item i {
            font-size: 3rem;
            color: #64d9aa;
            margin-bottom: 20px;
        }

        .icon-item h5 {
            color: #333;
            font-size: 1.1rem;
            margin-top: 15px;
        }

        .info-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .info-section h3 {
            color: #333;
            margin-bottom: 40px;
            font-size: 2rem;
        }

        /* Stili per i risultati di ricerca */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 15px;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            max-height: 400px;
            overflow-y: auto;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 24px;
            padding: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .graph-section {
            background-color: #f8f9fa;
            padding: 80px 20px;
        }

        .graph-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .graph-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .graph-placeholder {
            background: #ffffff;
            border-radius: 15px;
            height: 300px;
            margin: 30px auto;
            max-width: 800px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .graph-placeholder i {
            font-size: 48px;
            color: #64d9aa;
        }

        .graph-description {
            font-size: 16px;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .info-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .info-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .info-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .info-description {
            font-size: 18px;
            color: #666;
            margin-bottom: 60px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .info-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .info-feature {
            padding: 30px;
            background: white;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .info-feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(100, 217, 170, 0.1);
        }

        .info-feature i {
            font-size: 36px;
            color: #64d9aa;
            margin-bottom: 20px;
        }

        .info-feature h5 {
            font-size: 20px;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        .placeholder-image {
            background: linear-gradient(45deg, #f1f1f1 25%, #e9e9e9 25%, #e9e9e9 50%, #f1f1f1 50%, #f1f1f1 75%, #e9e9e9 75%, #e9e9e9 100%);
            background-size: 20px 20px;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 24px;
        }

        @media (max-width: 768px) {
            .info-features {
                grid-template-columns: 1fr;
            }

            .graph-title, .info-title {
                font-size: 24px;
            }

            .info-description {
                font-size: 16px;
            }
        }
    </style>
@endpush

<x-shop::layouts>
    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <!-- Hero Section -->
    <div class="hero">
        <h1>IL PUNTO DI PARTENZA PER LO SHOPPING FARMACEUTICO</h1>
        <div class="search-container">
            <i class="icon-search"></i>
            <input 
                type="search" 
                id="searchInput"
                placeholder="Cerca farmaci, integratori, cosmetici..." 
                autocomplete="off"
            >
            <div class="search-results" id="searchResults"></div>
        </div>
    </div>

    <div id="main">
    <section class="icons-section">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <div class="icon-item">
                        <i class="fas fa-search"></i>
                        <h5>Cerca prodotti in tutti i negozi di farmaci online</h5>
                    </div>
                </div>
                <div class="col-4">
                    <div class="icon-item">
                        <i class="fas fa-shopping-cart"></i>
                        <h5>Crea il tuo carrello</h5>
                    </div>
                </div>
                <div class="col-4">
                    <div class="icon-item">
                        <i class="fas fa-percentage"></i>
                        <h5>Compra la combinazione di prezzi più bassa</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <section class="graph-section">
            <div class="graph-container">
                <h2 class="graph-title">Risparmio medio dei nostri clienti</h2>
                <div class="graph-placeholder">
                    <i class="fas fa-chart-line"></i>
                </div>
                <p class="graph-description">
                    I clienti che acquistano un carrello con elevata quantità di prodotti ottengono uno sconto maggiore.
                </p>
            </div>
        </section>

        <section class="info-section">
            <div class="info-container">
                <h3 class="info-title">CHI SIAMO E COSA FACCIAMO</h3>
                <p class="info-description">
                    <strong>FarmaConveniente</strong> aggrega i prezzi di 2.000.000.000.000 farmaci in libera vendita 
                    e ti offre la migliore opzione di acquisto sul tuo carrello.
                </p>
                
                <div class="info-features">
                    <div class="info-feature">
                        <i class="fas fa-magnifying-glass"></i>
                        <h5>CERCA</h5>
                    </div>
                    <div class="info-feature">
                        <i class="fas fa-scale-balanced"></i>
                        <h5>CONFRONTA</h5>
                    </div>
                    <div class="info-feature">
                        <i class="fas fa-object-group"></i>
                        <h5>AGGREGA</h5>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            let originalContent = '';
            let searchTimeout = null;

            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const mainContent = document.getElementById('main');

            // Salva il contenuto originale al caricamento
            document.addEventListener('DOMContentLoaded', function() {
                if (mainContent) {
                    originalContent = mainContent.innerHTML;
                }
            });

            // Gestione dell'input di ricerca
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                
                const query = e.target.value;
                
                if (query.length < 3) {
                    searchResults.style.display = 'none';
                    if (originalContent && mainContent) {
                        mainContent.innerHTML = originalContent;
                    }
                    return;
                }

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            // Gestione del click fuori dai risultati
            document.addEventListener('click', function(e) {
                if (!searchResults.contains(e.target) && e.target !== searchInput) {
                    searchResults.style.display = 'none';
                }
            });

            // Funzione di ricerca con Typesense
            async function performSearch(query) {
                try {
                    const response = await fetch(`/api/search?q=${query}`);
                    const data = await response.json();
                    const products = data?.hits || [];

                    if (products.length > 0) {
                        updateMainContent(products);
                        updateSearchDropdown(products);
                    } else {
                        if (originalContent && mainContent) {
                            mainContent.innerHTML = originalContent;
                        }
                        searchResults.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Errore durante la ricerca:', error);
                    searchResults.style.display = 'none';
                }
            }

            // Aggiorna il dropdown dei risultati
            function updateSearchDropdown(products) {
                const limitedProducts = products.slice(0, 5);
                
                searchResults.innerHTML = limitedProducts.map(product => `
                    <a href="${product.url_key}" class="flex items-center p-4 hover:bg-gray-50 transition-colors">
                        <img src="${product.image}" class="w-16 h-16 object-cover rounded" alt="${product.name}">
                        <div class="ml-4 flex-1">
                            <div class="font-medium text-gray-900">${product.name}</div>
                            <div class="text-gray-600">
                                ${product.special_price 
                                    ? `<span class="line-through text-sm">${product.formatted_price}</span>
                                       <span class="text-red-600 ml-2">${product.formatted_special_price}</span>`
                                    : `<span>${product.formatted_price}</span>`
                                }
                            </div>
                        </div>
                    </a>
                `).join('');

                searchResults.style.display = 'block';
            }

            // Aggiorna la griglia dei prodotti
            function updateMainContent(products) {
                if (!mainContent) return;

                mainContent.innerHTML = `
                    <div class="product-grid">
                        ${products.map(product => `
                            <a href="${product.url_key}" class="block">
                                <div class="rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                                    <div class="relative pb-[100%]">
                                        <img 
                                            src="${product.image}" 
                                            class="absolute inset-0 w-full h-full object-cover"
                                            alt="${product.name}"
                                        >
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-medium text-lg mb-2">${product.name}</h3>
                                        <div class="flex items-center justify-between">
                                            ${product.special_price 
                                                ? `<span class="text-gray-500 line-through text-sm">${product.formatted_price}</span>
                                                   <span class="text-red-600 font-medium">${product.formatted_special_price}</span>`
                                                : `<span class="text-gray-900 font-medium">${product.formatted_price}</span>`
                                            }
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `).join('')}
                    </div>
                `;
            }
        </script>
    @endpush
</x-shop::layouts>