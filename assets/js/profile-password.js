(function () {
    const form = document.querySelector('[data-profile-password-form]');
    if (!form) {
        return;
    }

    const currentPassword = document.getElementById('currentPassword');
    const newPassword = document.getElementById('profileNewPassword');
    const confirmPassword = document.getElementById('profileConfirmPassword');
    const passwordWrap = document.getElementById('profilePasswordWrap');
    const confirmWrap = document.getElementById('profileConfirmWrap');
    const passwordBubble = document.getElementById('profilePasswordBubble');
    const strengthText = document.getElementById('profileStrengthText');
    const strengthCount = document.getElementById('profileStrengthCount');
    const barFill = document.getElementById('profileBarFill');
    const bubbleBarFill = document.getElementById('profileBubbleBarFill');
    const bubbleTitle = document.getElementById('profileBubbleTitle');
    const bubbleSub = document.getElementById('profileBubbleSub');
    const confirmMsg = document.getElementById('profileConfirmMsg');
    const submitBtn = document.getElementById('profilePasswordSubmit');
    const formMessage = document.querySelector('[data-profile-password-message]');

    const rules = [
        { id: 'profileRuleLength', test: value => value.length >= 8 },
        { id: 'profileRuleUpper', test: value => /[A-Z]/.test(value) },
        { id: 'profileRuleLower', test: value => /[a-z]/.test(value) },
        { id: 'profileRuleNumber', test: value => /\d/.test(value) },
        { id: 'profileRuleSpecial', test: value => /[^A-Za-z0-9\s]/.test(value) },
        { id: 'profileRuleNoSpaces', test: value => !/\s/.test(value) }
    ];

    function isPasswordValid() {
        return newPassword.value.length > 0 && rules.every(rule => rule.test(newPassword.value));
    }

    function getProgressColor(validCount) {
        if (validCount === rules.length) {
            return '#34d399';
        }
        if (validCount >= 3) {
            return '#fbbf24';
        }
        return '#fb7185';
    }

    function hideFormMessage() {
        formMessage.textContent = '';
        formMessage.classList.remove('show', 'error');
    }

    function showFormMessage(message) {
        formMessage.textContent = message;
        formMessage.classList.add('show', 'error');
    }

    function updatePasswordUI() {
        const value = newPassword.value;
        const validCount = rules.reduce((count, rule) => {
            const valid = value.length > 0 && rule.test(value);
            const element = document.getElementById(rule.id);
            const icon = element.querySelector('.profile-rule-icon');

            element.classList.toggle('valid', valid);
            icon.textContent = valid ? '\u2713' : 'x';

            return valid ? count + 1 : count;
        }, 0);
        const progress = (validCount / rules.length) * 100;
        const passwordIsValid = isPasswordValid();

        barFill.style.width = progress + '%';
        bubbleBarFill.style.width = progress + '%';
        barFill.style.background = getProgressColor(validCount);
        bubbleBarFill.style.background = getProgressColor(validCount);
        strengthCount.textContent = `${validCount}/${rules.length}`;

        strengthText.className = 'profile-strength-text';
        if (validCount === 0) {
            strengthText.textContent = 'Digite uma senha segura';
            strengthText.classList.add('weak');
        } else if (validCount <= 2) {
            strengthText.textContent = 'Senha fraca';
            strengthText.classList.add('weak');
        } else if (validCount <= 5) {
            strengthText.textContent = 'Quase lá';
            strengthText.classList.add('medium');
        } else {
            strengthText.textContent = 'Senha forte';
            strengthText.classList.add('strong');
        }

        passwordWrap.classList.toggle('valid', passwordIsValid);
        bubbleTitle.textContent = passwordIsValid ? 'Senha forte' : 'Força da senha';
        bubbleSub.textContent = passwordIsValid
            ? 'Tudo certo. Agora confirme a nova senha.'
            : `${validCount} de ${rules.length} requisitos`;

        updateConfirmUI();
    }

    function updateConfirmUI() {
        const passwordIsValid = isPasswordValid();
        const confirmHasValue = confirmPassword.value.length > 0;
        const passwordsMatch = confirmHasValue && newPassword.value === confirmPassword.value;
        const currentFilled = currentPassword.value.trim() !== '';
        const canSubmit = currentFilled && passwordIsValid && passwordsMatch;

        confirmWrap.classList.remove('valid', 'error');
        confirmMsg.classList.remove('show', 'valid', 'error');

        if (confirmHasValue || document.activeElement === confirmPassword) {
            confirmMsg.classList.add('show');

            if (!passwordIsValid) {
                confirmWrap.classList.add('error');
                confirmMsg.classList.add('error');
                confirmMsg.textContent = 'A nova senha ainda precisa cumprir todos os requisitos.';
            } else if (passwordsMatch) {
                confirmWrap.classList.add('valid');
                confirmMsg.classList.add('valid');
                confirmMsg.textContent = 'As senhas coincidem.';
            } else {
                confirmWrap.classList.add('error');
                confirmMsg.classList.add('error');
                confirmMsg.textContent = 'A confirmação ainda não confere.';
            }
        }

        submitBtn.disabled = !canSubmit;
        submitBtn.classList.toggle('enabled', canSubmit);
    }

    newPassword.addEventListener('focus', () => {
        passwordBubble.classList.add('show');
    });
    newPassword.addEventListener('input', () => {
        hideFormMessage();
        updatePasswordUI();
    });

    confirmPassword.addEventListener('focus', () => {
        passwordBubble.classList.remove('show');
        updateConfirmUI();
    });
    confirmPassword.addEventListener('input', () => {
        hideFormMessage();
        updateConfirmUI();
    });

    currentPassword.addEventListener('focus', () => {
        passwordBubble.classList.remove('show');
    });
    currentPassword.addEventListener('input', () => {
        hideFormMessage();
        updateConfirmUI();
    });

    document.querySelectorAll('.profile-password-toggle').forEach(button => {
        button.addEventListener('mousedown', event => {
            event.preventDefault();
        });

        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.target);
            const hidden = target.type === 'password';

            target.type = hidden ? 'text' : 'password';
            button.textContent = hidden ? 'Ocultar' : 'Mostrar';
        });
    });

    form.addEventListener('submit', event => {
        hideFormMessage();

        if (currentPassword.value.trim() === '') {
            event.preventDefault();
            showFormMessage('Informe a senha atual.');
            return;
        }

        if (!isPasswordValid()) {
            event.preventDefault();
            passwordBubble.classList.add('show');
            showFormMessage('A nova senha ainda não atende a todos os requisitos.');
            return;
        }

        if (newPassword.value !== confirmPassword.value) {
            event.preventDefault();
            showFormMessage('A confirmação precisa ser igual à nova senha.');
        }
    });

    const modal = document.getElementById('passwordModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', () => {
            form.reset();
            hideFormMessage();
            passwordBubble.classList.remove('show');
            updatePasswordUI();
        });
    }

    updatePasswordUI();
})();
