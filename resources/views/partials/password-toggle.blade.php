<style>
    .password-toggle-wrapper {
        position: relative;
        display: block;
        width: 100%;
    }

    .password-toggle-input {
        padding-right: 2.75rem !important;
    }

    .password-toggle-btn {
        position: absolute;
        top: 50%;
        right: 0.65rem;
        transform: translateY(-50%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin: 0;
        border: 0;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        line-height: 1;
    }

    .password-toggle-btn:hover {
        color: #1e293b;
    }

    .password-toggle-btn svg {
        width: 20px;
        height: 20px;
        pointer-events: none;
    }
</style>

<script>
    (() => {
        const eye = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        `;

        const eyeOff = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.76 21.76 0 0 1 5.06-5.94"></path>
                <path d="M9.9 4.24A10.93 10.93 0 0 1 12 5c7 0 11 7 11 7a22.47 22.47 0 0 1-2.16 3.19"></path>
                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                <path d="M1 1l22 22"></path>
            </svg>
        `;

        const setupPasswordToggles = () => {
            document.querySelectorAll('input[type="password"]').forEach((input) => {
                if (input.dataset.passwordToggleReady === '1') {
                    return;
                }

                input.dataset.passwordToggleReady = '1';

                const wrapper = document.createElement('div');
                wrapper.className = 'password-toggle-wrapper';
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);
                input.classList.add('password-toggle-input');

                const toggleButton = document.createElement('button');
                toggleButton.type = 'button';
                toggleButton.className = 'password-toggle-btn';
                toggleButton.setAttribute('aria-label', 'Afficher le mot de passe');
                toggleButton.innerHTML = eye;

                toggleButton.addEventListener('click', () => {
                    const isPasswordHidden = input.type === 'password';
                    input.type = isPasswordHidden ? 'text' : 'password';
                    toggleButton.setAttribute(
                        'aria-label',
                        isPasswordHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
                    );
                    toggleButton.innerHTML = isPasswordHidden ? eyeOff : eye;
                });

                wrapper.appendChild(toggleButton);
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupPasswordToggles);
        } else {
            setupPasswordToggles();
        }
    })();
</script>
