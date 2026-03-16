<style>
    #splash-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #1a1a1a;
        /* Couleur sombre pro */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        /* Toujours au-dessus */
        transition: opacity 0.5s ease, visibility 0.5s;
    }

    .loader-content {
        text-align: center;
    }

    .splash-logo {
        width: 80px;
        margin-bottom: 20px;
        animation: pulse 2s infinite;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(255, 255, 255, 0.1);
        border-left-color: #fff;
        /* #3498db;*/
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    .loading-text {
        color: white;
        font-family: 'Inter', sans-serif;
        margin-top: 15px;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }

    /* Animations */
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            opacity: 0.7;
        }

        50% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(0.95);
            opacity: 0.7;
        }
    }

    /* Classe pour masquer le splash */
    .splash-hidden {
        opacity: 0;
        visibility: hidden;
    }
</style>
<div id="splash-screen">
    <div class="loader-content">
        <img src="./assets/img/C.png" alt="Logo" class="splash-logo">
        <div class="spinner"></div>
        <p class="loading-text">Chargement de CIO Pointage...</p>
    </div>
</div>


<script>
    window.addEventListener('load', function() {
        const splash = document.getElementById('splash-screen');

        // On ajoute un léger délai de 500ms pour éviter un flash trop rapide
        // si la connexion est ultra-rapide (meilleure UX)
        setTimeout(() => {
            splash.classList.add('splash-hidden');
        }, 200);
    });
</script>