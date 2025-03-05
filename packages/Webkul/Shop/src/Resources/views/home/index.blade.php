@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

    /* Hero section with the green gradient background */
    .hero {
        background-color: #64d9aa;
        color: #fff;
        text-align: center;
        padding: 60px 20px;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        z-index: 10;
    }

    .hero h1 {
        font-size: 3.5rem;
        margin-bottom: 30px;
        font-weight: 700;
        color: #F4F3C6;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-transform: uppercase;
    }

    /* Search container styling */
    .search-container {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 10;
    }

    .search-container input {
        width: 100%;
        padding: 20px 60px;
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        color: #000 !important;
        caret-color: #000 !important;
    }

    .search-container input::placeholder {
        color: #777;
    }

    .search-container input:focus {
        outline: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .search-container i {
        position: absolute;
        left: 0px;
        top: 50%;
        transform: translateY(-50%);
        color: #64d9aa;
        font-size: 1.2rem;
    }

    /* Search button styling */
    .search-button {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background-color: #343a40;
        color: white;
        border: none;
        height: 40px;
        width: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Features section with icons */
    .features-section {
        background-color: #f0f7f4;
        padding: 40px 0;
    }

    .features-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }

    .feature-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 0 20px;
        margin: 20px 0;
        max-width: 300px;
    }

    .feature-icon {
        color: #64d9aa;
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .feature-text {
        font-size: 18px;
        font-weight: 500;
        color: #64d9aa;
    }

    /* Graph section */
    .graph-section {
        background-color: #f8f9fa;
        padding: 60px 20px;
    }

    .graph-container {
        max-width: 1000px;
        margin: 0 auto;
        text-align: center;
    }

    .graph-title {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 30px;
        font-weight: 600;
    }

    .graph-image {
        width: 100%;
        max-width: 700px;
        height: auto;
        margin: 0 auto 20px;
    }

    .graph-description {
        max-width: 600px;
        margin: 0 auto;
        font-size: 0.9rem;
        color: #666;
    }

    /* About section */
    .about-section {
        background: #F6F6E5;
        padding: 60px 20px;
        text-align: center;
    }

    .about-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .about-title {
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 20px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .about-text {
        max-width: 800px;
        margin: 0 auto 40px;
        font-size: 1rem;
        color: #555;
    }

    .company-name {
        font-weight: 600;
    }

    .action-items {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 30px;
        margin-top: 40px;
    }

    .action-item {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        width: 200px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .action-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .action-icon {
        font-size: 2rem;
        color: #64d9aa;
        margin-bottom: 15px;
    }

    .action-title {
        font-size: 1.2rem;
        color: #333;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Footer section */
    .footer {
        background-color: #343a40;
        color: #fff;
        padding: 40px 20px;
        text-align: center;
    }

    .footer-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .footer-info {
        margin-bottom: 20px;
    }

    .footer-links a {
        color: #fff;
        opacity: 0.8;
        margin: 0 10px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: opacity 0.3s ease;
    }

    .footer-links a:hover {
        opacity: 1;
    }

    /* Search results */
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

    .search-results a {
        color: #000 !important;
        text-decoration: none;
    }

    .search-results .flex {
        display: flex;
        align-items: center;
    }

    .search-results .p-4 {
        padding: 1rem;
    }

    .search-results .hover\:bg-gray-50:hover {
        background-color: #f9f9f9;
    }

    .search-results .w-16 {
        width: 4rem;
    }

    .search-results .h-16 {
        height: 4rem;
    }

    .search-results .object-cover {
        object-fit: cover;
    }

    .search-results .rounded {
        border-radius: 0.25rem;
    }

    .search-results .block {
        display: block;
    }

    /* Product Grid */
    .product-grid-section {
        padding: 40px 0;
        background-color: #f9f9f9;
    }
                
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 24px;
        padding: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }
                
    .product-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
                
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
                
    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
                
    .product-info {
        padding: 16px;
    }
                
    .product-title {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 8px;
        color: #333;
    }
                
    .product-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
                
    .original-price {
        text-decoration: line-through;
        color: #888;
        font-size: 14px;
    }
                
    .discounted-price {
        color: #e53e3e;
        font-weight: 600;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 1.8rem;
        }
        
        .feature-item {
            flex: 0 0 100%;
        }
        
        .action-items {
            flex-direction: column;
            align-items: center;
        }
        
        .action-item {
            width: 100%;
            max-width: 300px;
        }
    }
</style>
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <!-- Hero Section -->
    <div class="hero">
        <h1>IL PUNTO DI PARTENZA <br>PER LO SHOPPING<br> FARMACEUTICO</h1>
        <div class="search-container" style="width: 100%;">
            <input 
                type="text"
                id="hero-search-input"
                name="query"
                class="search-input"
                value="{{ request('query') }}"
                minlength="{{ core()->getConfigData('catalog.products.search.min_query_length') }}"
                maxlength="{{ core()->getConfigData('catalog.products.search.max_query_length') }}"
                placeholder="Cerca farmaci, integratori, cosmetici..." 
                aria-label="Cerca prodotti"
                aria-required="true"
                pattern="[^\\]+"
                autocomplete="off"
                style="color: black !important; caret-color: black !important;"
                oninput="heroSearchProducts()"
            >
            <button class="search-button">
                <i class="fas fa-search" style="color: white; display: flex; justify-content: center; align-items: center; width: 100%;"></i>
            </button>
            <div id="hero-search-results" class="search-results">
                <!-- Search results will be populated here -->
            </div>
        </div>
    </div>

    <div id="risultati">
        <!-- Features Section -->
        <section class="features-section">
            <div class="features-container">
                <div class="feature-item">
                <img src="{{ asset('1.png') }}" alt="Search icon" class="action-icon">
                <div class="feature-text">Cerca prodotti in tutti i negozi di farmaci online</div>
                </div>
                <div class="feature-item">
                <img src="{{ asset('2.png') }}" alt="Search icon" class="action-icon">
                <div class="feature-text">Crea il tuo carrello</div>
                </div>
                <div class="feature-item">
                <img src="{{ asset('3.png') }}" alt="Search icon" class="action-icon">
                <div class="feature-text">Compra la combinazione di prezzi più bassa</div>
                </div>
            </div>
        </section>

        <!-- Graph Section -->
        <section class="graph-section">
            <div class="graph-container">
            <h2 class="graph-title">Risparmio medio dei nostri clienti</h2>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 30px;">
            <div style="width: 65%;">
            <svg width="700" height="300" viewBox="0 0 700 300" xmlns="http://www.w3.org/2000/svg">
            <!-- Background grid -->
            <g stroke="#ddd" stroke-dasharray="4">
            <!-- Horizontal grid lines -->
            <line x1="50" y1="50" x2="650" y2="50" stroke-width="1"/>
            <line x1="50" y1="100" x2="650" y2="100" stroke-width="1"/>
            <line x1="50" y1="150" x2="650" y2="150" stroke-width="1"/>
            <line x1="50" y1="200" x2="650" y2="200" stroke-width="1"/>
            <line x1="50" y1="250" x2="650" y2="250" stroke-width="1"/>
            
            <!-- Vertical grid lines -->
            <line x1="100" y1="50" x2="100" y2="250" stroke-width="1"/>
            <line x1="200" y1="50" x2="200" y2="250" stroke-width="1"/>
            <line x1="300" y1="50" x2="300" y2="250" stroke-width="1"/>
            <line x1="400" y1="50" x2="400" y2="250" stroke-width="1"/>
            <line x1="500" y1="50" x2="500" y2="250" stroke-width="1"/>
            <line x1="600" y1="50" x2="600" y2="250" stroke-width="1"/>
            </g>
            
            <!-- X-axis -->
            <line x1="50" y1="250" x2="650" y2="250" stroke="#333" stroke-width="2"/>
            
            <!-- Y-axis -->
            <line x1="50" y1="50" x2="50" y2="250" stroke="#333" stroke-width="2"/>
            
            <!-- X-axis labels -->
            <text x="100" y="270" text-anchor="middle" fill="#666" font-size="12">1</text>
            <text x="200" y="270" text-anchor="middle" fill="#666" font-size="12">2</text>
            <text x="300" y="270" text-anchor="middle" fill="#666" font-size="12">3</text>
            <text x="400" y="270" text-anchor="middle" fill="#666" font-size="12">4</text>
            <text x="500" y="270" text-anchor="middle" fill="#666" font-size="12">5</text>
            <text x="600" y="270" text-anchor="middle" fill="#666" font-size="12">6</text>
            
            <!-- X-axis title -->
            <text x="350" y="290" text-anchor="middle" fill="#333" font-size="14">Numero prodotti a carrello</text>
            
            <!-- Y-axis labels -->
            <text x="40" y="250" text-anchor="end" fill="#666" font-size="12">0%</text>
            <text x="40" y="200" text-anchor="end" fill="#666" font-size="12">10%</text>
            <text x="40" y="150" text-anchor="end" fill="#666" font-size="12">20%</text>
            <text x="40" y="100" text-anchor="end" fill="#666" font-size="12">30%</text>
            <text x="40" y="50" text-anchor="end" fill="#666" font-size="12">40%</text>
            
            <!-- Data points with dark blue backgrounds -->
            <circle cx="100" cy="230" r="16" fill="#1a365d"/>
            <circle cx="200" cy="200" r="16" fill="#1a365d"/>
            <circle cx="300" cy="170" r="16" fill="#1a365d"/>
            <circle cx="400" cy="130" r="16" fill="#1a365d"/>
            <circle cx="500" cy="100" r="16" fill="#1a365d"/>
            <circle cx="600" cy="80" r="16" fill="#1a365d"/>
            
            <!-- Line connecting points -->
            <path d="M100,230 L200,200 L300,170 L400,130 L500,100 L600,80" stroke="#64d9aa" stroke-width="3" fill="none"/>
            
            <!-- Text inside circles -->
            <text x="100" y="234" text-anchor="middle" fill="white" font-size="12" font-weight="bold">5%</text>
            <text x="200" y="204" text-anchor="middle" fill="white" font-size="12" font-weight="bold">10%</text>
            <text x="300" y="174" text-anchor="middle" fill="white" font-size="12" font-weight="bold">20%</text>
            <text x="400" y="134" text-anchor="middle" fill="white" font-size="12" font-weight="bold">25%</text>
            <text x="500" y="104" text-anchor="middle" fill="white" font-size="12" font-weight="bold">30%</text>
            <text x="600" y="84" text-anchor="middle" fill="white" font-size="12" font-weight="bold">35%</text>
            </svg>
            </div>
            <div style="width: 30%; display: flex; align-items: flex-start; padding: 20px;">
            <p style="margin: 0; line-height: 1.6; text-align: left;">I clienti che acquistano <b>un carrello con elevata quantità di prodotti</b> ottengono uno sconto maggiore.
            </p>
            </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section">
            <div class="about-container">
                <h3 class="about-title">CHI SIAMO E COSA FACCIAMO</h3>
                <img src="{{ asset('grafico.png') }}" alt="Farmaconviene" style="width: 600px; margin: 20px auto; display: block;">
                <div style="border: 4px solid #000; border-bottom: none; border-left:none; border-right:none; padding: 20px; padding-bottom: 0; margin-bottom: 0;">
                <p class="about-text">
                    <span class="company-name"><b>Farmaconviene</b></span> aggrega i prezzi di <b>2.000.000.000.000 di farmaci</b> in libera vendita 
                    e ti offre <b>la migliore opzione di acquisto sul tuo carrello</b>.
                </p>
                
                <div class="action-items">
                    
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-info">
                <p>Farmaconviene - Via dell'Industria 14, 20100 Milano</p>
                <p>Numero di registro delle imprese: P.IVA 12345678910</p>
            </div>
            <div class="footer-links">
                <a href="/chi-siamo">Chi siamo</a>
                <a href="/cosa-facciamo">Cosa facciamo</a>
                <a href="/come-funziona">Come funziona</a>
            </div>
        </div>
    </footer>

    <script>
        async function heroSearchProducts() {
            const query = document.getElementById('hero-search-input').value;
            const resultsContainer = document.getElementById('hero-search-results');
            const mainContent = document.getElementById('risultati');
            
            // Save original content if not already saved
            if (!window.originalMainContent && query.length === 3) {
                window.originalMainContent = mainContent.innerHTML;
            }
    
            if (query.length < 3) {
                resultsContainer.style.display = 'none';
                resultsContainer.innerHTML = '';
                
                // Restore original content if available
                if (window.originalMainContent) {
                    mainContent.innerHTML = window.originalMainContent;
                }
                return;
            }
    
            try {
                const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                const products = data?.products || [];
    
                // Display results in dropdown
                if (products.length > 0) {
                    resultsContainer.style.display = 'block';
                    resultsContainer.innerHTML = products.map(product => `
                        <a href="/${product.product_url}" class="block">
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <img src="${product.base_image}" class="w-16 h-16 object-cover rounded">
                                    <div style="margin-left: 15px;">
                                        <div style="font-weight: 500;">${product.name}</div>
                                        <div style="color: #666;">
                                            <span style="color: #e53e3e;">${product.formatted_price}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    `).join('');
                    
                    // Display results as grid in main page
                    const productsGrid = `
                        <section class="product-grid-section">
                            <div class="container">
                                <h2 style="text-align: center; margin: 1rem 0;">Risultati per "${query}"</h2>
                                <div class="product-grid">
                                    ${products.map(product => `
                                        <div class="product-card">
                                            <a href="/${product.product_url}">
                                                <img src="${product.base_image}" alt="${product.name}" class="product-image">
                                                <div class="product-info">
                                                    <h3 class="product-title">${product.name}</h3>
                                                    <div class="product-price">
                                                        <span class="discounted-price">Prezzi da: ${product.formatted_price}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </section>
                    `;
                    
                    // Replace main content while maintaining important sections
                    mainContent.innerHTML = productsGrid;
                } else {
                    // No results found
                    resultsContainer.style.display = 'block';
                    resultsContainer.innerHTML = '<div class="p-4 text-center">Nessun prodotto trovato</div>';
                    
                    mainContent.innerHTML = `
                        <section class="no-results-section">
                            <div style="text-align: center; padding: 4rem 1rem;">
                                <i class="fas fa-search" style="font-size: 48px; color: #64d9aa;"></i>
                                <h2 style="margin-top: 1rem;">Nessun prodotto trovato per "${query}"</h2>
                                <p style="margin-top: 0.5rem;">Prova a cercare con un termine diverso o esplora le categorie popolari</p>
                            </div>
                        </section>
                    `;
                }
            } catch (error) {
                console.error('Errore nella ricerca:', error);
                resultsContainer.style.display = 'none';
                resultsContainer.innerHTML = '';
            }
        }
    
        // Hide results when clicking outside search bar
        document.addEventListener('click', function(e) {
            const resultsContainer = document.getElementById('hero-search-results');
            const searchInput = document.getElementById('hero-search-input');
            
            if (resultsContainer && !resultsContainer.contains(e.target) && e.target !== searchInput) {
                resultsContainer.style.display = 'none';
            }
        });
        
        // Show results when clicking in search input
        document.getElementById('hero-search-input').addEventListener('click', function() {
            const query = this.value;
            const resultsContainer = document.getElementById('hero-search-results');
            
            if (query.length >= 3 && resultsContainer.innerHTML.trim() !== '') {
                resultsContainer.style.display = 'block';
            }
        });
    </script>
</x-shop::layouts>