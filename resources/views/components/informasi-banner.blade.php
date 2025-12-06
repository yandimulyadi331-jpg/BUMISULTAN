<!-- Informasi Banner Component -->
<div id="informasi-banner-overlay" style="display: none;">
    <div id="informasi-banner-container">
        <div id="informasi-banner-close">
            <i class="ti ti-x"></i>
        </div>
        <div id="informasi-banner-content">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<style>
    #informasi-banner-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease-in-out;
    }

    #informasi-banner-container {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.4s ease-out;
    }

    #informasi-banner-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    #informasi-banner-close:hover {
        background: #ff4444;
        color: white;
        transform: scale(1.1);
    }

    #informasi-banner-close i {
        font-size: 20px;
        font-weight: bold;
    }

    #informasi-banner-content {
        max-height: 85vh;
        overflow-y: auto;
        padding: 20px;
    }

    #informasi-banner-content img {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    #informasi-banner-content .banner-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    #informasi-banner-content .banner-text {
        font-size: 16px;
        line-height: 1.6;
        color: #666;
        white-space: pre-wrap;
    }

    #informasi-banner-content .banner-link {
        display: inline-block;
        margin-top: 15px;
        padding: 12px 30px;
        background: #32745e;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    #informasi-banner-content .banner-link:hover {
        background: #3ab58c;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(50, 116, 94, 0.3);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        #informasi-banner-container {
            max-width: 95%;
            max-height: 85%;
        }

        #informasi-banner-content {
            padding: 15px;
        }

        #informasi-banner-content .banner-title {
            font-size: 20px;
        }

        #informasi-banner-content .banner-text {
            font-size: 14px;
        }
    }
</style>

<script>
    // Informasi Banner Handler
    const InformasiBanner = {
        overlay: null,
        container: null,
        closeBtn: null,
        content: null,
        currentIndex: 0,
        informasiList: [],

        init: function() {
            console.log('Informasi Banner: Initializing...');
            this.overlay = document.getElementById('informasi-banner-overlay');
            this.container = document.getElementById('informasi-banner-container');
            this.closeBtn = document.getElementById('informasi-banner-close');
            this.content = document.getElementById('informasi-banner-content');

            if (!this.overlay || !this.container || !this.closeBtn || !this.content) {
                console.error('Informasi Banner: Required elements not found!');
                return;
            }

            // Event listeners
            this.closeBtn.addEventListener('click', () => this.closeCurrentBanner());
            this.overlay.addEventListener('click', (e) => {
                if (e.target === this.overlay) {
                    this.closeCurrentBanner();
                }
            });

            // Fetch ALL active informasi (always show every login)
            this.fetchAllInformasi();
        },

        fetchAllInformasi: function() {
            console.log('Informasi Banner: Fetching all active informasi...');
            fetch('/api/informasi/all', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Informasi Banner: Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Informasi Banner: Data received:', data);
                if (data.success && data.data.length > 0) {
                    this.informasiList = data.data;
                    this.showNextBanner();
                } else {
                    console.log('Informasi Banner: No active informasi found');
                }
            })
            .catch(error => {
                console.error('Informasi Banner: Error fetching informasi:', error);
            });
        },

        showNextBanner: function() {
            if (this.currentIndex < this.informasiList.length) {
                const info = this.informasiList[this.currentIndex];
                this.displayBanner(info);
                this.overlay.style.display = 'flex';
            } else {
                this.overlay.style.display = 'none';
            }
        },

        displayBanner: function(info) {
            let html = '';

            if (info.tipe === 'banner' && info.banner_url) {
                html = `
                    <h3 class="banner-title">${info.judul}</h3>
                    <img src="${info.banner_url}" alt="${info.judul}">
                    ${info.konten ? `<p class="banner-text mt-3">${info.konten}</p>` : ''}
                `;
            } else if (info.tipe === 'link' && info.link_url) {
                html = `
                    <h3 class="banner-title">${info.judul}</h3>
                    ${info.konten ? `<p class="banner-text">${info.konten}</p>` : ''}
                    <a href="${info.link_url}" class="banner-link" target="_blank">
                        <i class="ti ti-external-link me-2"></i>Buka Link
                    </a>
                `;
            } else {
                html = `
                    <h3 class="banner-title">${info.judul}</h3>
                    ${info.konten ? `<p class="banner-text">${info.konten}</p>` : ''}
                `;
            }

            this.content.innerHTML = html;
        },

        closeCurrentBanner: function() {
            const info = this.informasiList[this.currentIndex];
            
            // Optional: Mark as read for statistics (tidak mempengaruhi tampilan)
            fetch(`/api/informasi/${info.id}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            }).catch(error => {
                console.log('Log read skipped:', error);
            });
            
            // Langsung show next banner tanpa tunggu response
            this.currentIndex++;
            this.showNextBanner();
        }
    };

    // Initialize when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        InformasiBanner.init();
    });
</script>
