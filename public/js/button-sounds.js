(() => {
    const IMPORTANT_WORDS = [
        'enregistrer', 'sauvegarder', 'mettre a jour', 'mettre à jour', 'publier',
        'creer', 'créer', 'ajouter', 'accepter', 'refuser', 'valider',
        'inscription', 'submit', 'envoyer', 'supprimer', 'retirer'
    ];
    const DANGER_WORDS = ['supprimer', 'retirer', 'refuser', 'desactiver', 'désactiver', 'delete'];
    const SUCCESS_WORDS = ['enregistrer', 'sauvegarder', 'publier', 'accepter', 'valider', 'creer', 'créer', 'ajouter'];

    let audioCtx = null;
    let enabled = true;
    let lastPlayAt = 0;

    function getText(el) {
        return ((el?.innerText || el?.textContent || '') + '').trim().toLowerCase();
    }

    function getLabel(el) {
        const aria = (el.getAttribute('aria-label') || '').trim().toLowerCase();
        const title = (el.getAttribute('title') || '').trim().toLowerCase();
        return `${getText(el)} ${aria} ${title}`.trim();
    }

    function isDisabled(el) {
        return el.hasAttribute('disabled') || el.getAttribute('aria-disabled') === 'true';
    }

    function hasImportantIntent(el) {
        if (!el || isDisabled(el)) {
            return false;
        }

        if (el.dataset.sound === 'important' || el.dataset.sound === 'danger' || el.dataset.sound === 'success') {
            return true;
        }

        const tag = el.tagName.toLowerCase();
        const type = (el.getAttribute('type') || '').toLowerCase();
        const label = getLabel(el);

        if ((tag === 'button' || tag === 'input') && type === 'submit') {
            return true;
        }

        if (tag === 'button' || tag === 'a' || (tag === 'input' && (type === 'button' || type === 'submit'))) {
            return IMPORTANT_WORDS.some((word) => label.includes(word));
        }

        return false;
    }

    function classifyTone(el) {
        const explicit = el.dataset.sound;
        if (explicit === 'danger' || explicit === 'success' || explicit === 'important') {
            return explicit;
        }

        const label = getLabel(el);
        if (DANGER_WORDS.some((word) => label.includes(word))) {
            return 'danger';
        }
        if (SUCCESS_WORDS.some((word) => label.includes(word))) {
            return 'success';
        }
        return 'important';
    }

    function ensureAudioContext() {
        if (audioCtx) {
            return audioCtx;
        }
        const Ctx = window.AudioContext || window.webkitAudioContext;
        if (!Ctx) {
            enabled = false;
            return null;
        }
        audioCtx = new Ctx();
        return audioCtx;
    }

    function envelope(ctx, gain, start, attack, decay, peak) {
        gain.gain.setValueAtTime(0.0001, start);
        gain.gain.linearRampToValueAtTime(peak, start + attack);
        gain.gain.exponentialRampToValueAtTime(0.0001, start + attack + decay);
    }

    function playTone(kind) {
        if (!enabled) {
            return;
        }

        const now = Date.now();
        if (now - lastPlayAt < 90) {
            return;
        }
        lastPlayAt = now;

        const ctx = ensureAudioContext();
        if (!ctx) {
            return;
        }

        const start = ctx.currentTime + 0.01;
        const notes = {
            important: [760],
            success: [740, 980],
            danger: [430, 360],
        }[kind] || [760];

        notes.forEach((freq, i) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = kind === 'danger' ? 'square' : 'sine';
            osc.frequency.value = freq;
            osc.connect(gain);
            gain.connect(ctx.destination);

            const toneStart = start + i * 0.075;
            envelope(ctx, gain, toneStart, 0.008, 0.06, 0.035);

            osc.start(toneStart);
            osc.stop(toneStart + 0.085);
        });
    }

    function resolveActionTarget(target) {
        return target.closest('button, a, input[type="button"], input[type="submit"]');
    }

    document.addEventListener('click', (event) => {
        const el = resolveActionTarget(event.target);
        if (!el || !hasImportantIntent(el)) {
            return;
        }
        playTone(classifyTone(el));
    }, true);
})();
