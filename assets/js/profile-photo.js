(function () {
    const form = document.querySelector('[data-profile-photo-form]');
    if (!form) {
        return;
    }

    const input = form.querySelector('[data-profile-photo-input]');
    const submit = form.querySelector('[data-profile-photo-submit]');
    const fileName = form.querySelector('[data-profile-photo-name]');
    const editor = document.querySelector('[data-profile-photo-editor]');
    const toggle = form.querySelector('[data-profile-photo-toggle]');
    const bubble = form.querySelector('[data-profile-photo-bubble]');
    const avatar = document.querySelector('.profile-avatar');
    const originalAvatar = avatar ? avatar.innerHTML : '';
    const allowedTypes = ['image/png', 'image/jpeg', 'image/webp'];
    const maxBytes = 1024 * 1024;

    function setBubbleOpen(open) {
        if (!bubble || !toggle) {
            return;
        }

        bubble.hidden = !open;
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    if (toggle && bubble) {
        toggle.addEventListener('click', () => {
            setBubbleOpen(bubble.hidden);
        });

        document.addEventListener('click', event => {
            if (!editor || editor.contains(event.target)) {
                return;
            }

            setBubbleOpen(false);
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                setBubbleOpen(false);
                toggle.focus();
            }
        });
    }

    input.addEventListener('change', () => {
        const file = input.files && input.files[0] ? input.files[0] : null;
        setBubbleOpen(true);

        if (!file) {
            submit.disabled = true;
            fileName.textContent = 'PNG, JPG ou WEBP até 1 MB.';
            if (avatar) {
                avatar.innerHTML = originalAvatar;
            }
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            submit.disabled = true;
            fileName.textContent = 'Use uma imagem PNG, JPG ou WEBP.';
            input.value = '';
            return;
        }

        if (file.size > maxBytes) {
            submit.disabled = true;
            fileName.textContent = 'A foto deve ter no máximo 1 MB.';
            input.value = '';
            return;
        }

        submit.disabled = false;
        fileName.textContent = file.name;

        if (!avatar) {
            return;
        }

        const reader = new FileReader();
        reader.addEventListener('load', () => {
            avatar.classList.add('has-photo');
            avatar.innerHTML = '<img src="" alt="Prévia da nova foto de perfil" data-profile-photo-preview>';
            const preview = avatar.querySelector('[data-profile-photo-preview]');
            if (preview) {
                preview.src = String(reader.result || '');
            }
        });
        reader.readAsDataURL(file);
    });
})();
