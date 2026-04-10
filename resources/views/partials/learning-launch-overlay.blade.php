<style>
    .learning-launch-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background:
            radial-gradient(24rem 24rem at 20% 20%, rgba(16, 185, 129, 0.2), transparent 60%),
            radial-gradient(22rem 22rem at 80% 10%, rgba(14, 165, 233, 0.18), transparent 58%),
            rgba(2, 6, 23, 0.82);
        backdrop-filter: blur(12px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 220ms ease, visibility 220ms ease;
    }

    .learning-launch-overlay.is-active {
        opacity: 1;
        visibility: visible;
    }

    .learning-launch-card {
        width: min(100%, 30rem);
        border-radius: 1.5rem;
        border: 1px solid rgba(148, 163, 184, 0.28);
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.92), rgba(15, 23, 42, 0.84));
        box-shadow: 0 28px 70px rgba(2, 6, 23, 0.45);
        padding: 1.75rem;
        color: #e2e8f0;
        text-align: center;
    }

    .learning-launch-mark {
        position: relative;
        width: 7rem;
        height: 7rem;
        margin: 0 auto 1.25rem;
        display: grid;
        place-items: center;
    }

    .learning-launch-ring,
    .learning-launch-ring::before {
        position: absolute;
        inset: 0;
        border-radius: 999px;
    }

    .learning-launch-ring {
        border: 1px solid rgba(52, 211, 153, 0.36);
        animation: learning-pulse 1.8s ease-in-out infinite;
    }

    .learning-launch-ring::before {
        content: "";
        inset: 0.5rem;
        border: 2px dashed rgba(125, 211, 252, 0.85);
        animation: learning-spin 5s linear infinite;
    }

    .learning-launch-logo {
        width: 4.75rem;
        height: 4.75rem;
        border-radius: 1.25rem;
        object-fit: cover;
        box-shadow: 0 12px 32px rgba(16, 185, 129, 0.22);
        animation: learning-float 2.4s ease-in-out infinite;
    }

    .learning-launch-progress {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 0.5rem;
        border-radius: 999px;
        background: rgba(148, 163, 184, 0.2);
        margin-top: 1rem;
    }

    .learning-launch-progress::after {
        content: "";
        position: absolute;
        inset: 0;
        width: 42%;
        border-radius: inherit;
        background: linear-gradient(90deg, #10b981, #22c55e, #38bdf8);
        animation: learning-bar 1.2s ease-in-out infinite;
    }

    @keyframes learning-spin {
        to { transform: rotate(360deg); }
    }

    @keyframes learning-float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-4px) rotate(4deg); }
    }

    @keyframes learning-pulse {
        0%, 100% { transform: scale(1); opacity: 0.9; }
        50% { transform: scale(1.06); opacity: 1; }
    }

    @keyframes learning-bar {
        0% { transform: translateX(-120%); }
        100% { transform: translateX(320%); }
    }
</style>

<div id="learningLaunchOverlay" class="learning-launch-overlay" aria-hidden="true">
    <div class="learning-launch-card">
        <div class="learning-launch-mark" aria-hidden="true">
            <div class="learning-launch-ring"></div>
            <img src="{{ asset('images/logo.jpeg') }}" alt="" class="learning-launch-logo">
        </div>
        <p class="text-xs uppercase tracking-[0.28em] text-emerald-200 font-semibold">LEEDCOURSE</p>
        <h2 id="learningLaunchTitle" class="mt-3 text-2xl font-extrabold">Preparation du suivi</h2>
        <p id="learningLaunchMessage" class="mt-2 text-sm text-slate-300">Initialisation de votre espace d apprentissage.</p>
        <div class="learning-launch-progress" aria-hidden="true"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const overlay = document.getElementById('learningLaunchOverlay');
        if (!overlay) {
            return;
        }

        const titleNode = document.getElementById('learningLaunchTitle');
        const messageNode = document.getElementById('learningLaunchMessage');
        const defaultTitle = titleNode.textContent;
        const defaultMessage = messageNode.textContent;

        const activateOverlay = (node) => {
            titleNode.textContent = node?.dataset.launchTitle || defaultTitle;
            messageNode.textContent = node?.dataset.launchMessage || defaultMessage;
            overlay.classList.add('is-active');
            overlay.setAttribute('aria-hidden', 'false');
        };

        document.querySelectorAll('[data-learning-launch]').forEach((node) => {
            if (node.tagName === 'FORM') {
                node.addEventListener('submit', () => activateOverlay(node));
                return;
            }

            node.addEventListener('click', (event) => {
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                    return;
                }

                if (node.tagName === 'A' && node.target === '_blank') {
                    return;
                }

                activateOverlay(node);
            });
        });
    });
</script>
