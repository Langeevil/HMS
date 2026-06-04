(function () {
    const form = document.querySelector('[data-password-form]');
    if (!form) {
        return;
    }

    const nameField = document.getElementById('nome');
    const usernameField = document.getElementById('username');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const passwordWrap = document.getElementById('passwordWrap');
    const confirmWrap = document.getElementById('confirmWrap');
    const passwordBubble = document.getElementById('passwordBubble');
    const strengthText = document.getElementById('strengthText');
    const strengthCount = document.getElementById('strengthCount');
    const barFill = document.getElementById('barFill');
    const bubbleBarFill = document.getElementById('bubbleBarFill');
    const bubbleTitle = document.getElementById('bubbleTitle');
    const bubbleSub = document.getElementById('bubbleSub');
    const connector = document.getElementById('connector');
    const confirmMsg = document.getElementById('confirmMsg');
    const submitBtn = document.getElementById('submitBtn');
    const lockBox = document.getElementById('lockBox');
    const lockIcon = document.getElementById('lockIcon');
    const formValidationMessage = document.getElementById('formValidationMessage');
    const normalFields = document.querySelectorAll('.normal-field');

    const rules = [
        { id: 'ruleLength', test: value => value.length >= 8 },
        { id: 'ruleUpper', test: value => /[A-Z]/.test(value) },
        { id: 'ruleLower', test: value => /[a-z]/.test(value) },
        { id: 'ruleNumber', test: value => /\d/.test(value) },
        { id: 'ruleSpecial', test: value => /[^A-Za-z0-9\s]/.test(value) },
        { id: 'ruleNoSpaces', test: value => !/\s/.test(value) }
    ];

    function evaluateRules(value) {
        return rules.map(rule => {
            const valid = rule.test(value);
            return {
                id: rule.id,
                valid,
                displayValid: value.length > 0 && valid
            };
        });
    }

    function isPasswordValid() {
        return password.value.length > 0 && rules.every(rule => rule.test(password.value));
    }

    function updatePasswordUI() {
        const value = password.value;
        const evaluatedRules = evaluateRules(value);
        const validCount = evaluatedRules.filter(rule => rule.displayValid).length;
        const progress = (validCount / rules.length) * 100;
        const passwordIsValid = isPasswordValid();

        evaluatedRules.forEach(rule => {
            const element = document.getElementById(rule.id);
            const icon = element.querySelector('.rule-icon');

            element.classList.toggle('valid', rule.displayValid);
            icon.textContent = rule.displayValid ? '\u2713' : 'x';
        });

        barFill.style.width = progress + '%';
        bubbleBarFill.style.width = progress + '%';
        strengthCount.textContent = `${validCount}/${rules.length}`;
        barFill.style.background = getProgressColor(validCount);
        bubbleBarFill.style.background = getProgressColor(validCount);

        strengthText.className = 'strength-text';
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
        lockBox.classList.toggle('valid', passwordIsValid);
        lockIcon.className = passwordIsValid ? 'bi bi-lock-fill' : 'bi bi-unlock-fill';
        bubbleTitle.textContent = passwordIsValid ? 'Senha forte' : 'Força da senha';
        bubbleSub.textContent = passwordIsValid
            ? 'Tudo certo. Agora repita a senha.'
            : `${validCount} de ${rules.length} requisitos`;

        updateConfirmUI();
    }

    function updateConfirmUI() {
        const passwordIsValid = isPasswordValid();
        const confirmHasValue = confirmPassword.value.length > 0;
        const passwordsMatch = confirmHasValue && password.value === confirmPassword.value;

        confirmWrap.classList.remove('valid', 'error');
        connector.classList.remove('show', 'valid', 'error');
        confirmMsg.classList.remove('show', 'valid', 'error');

        if (confirmHasValue || document.activeElement === confirmPassword) {
            connector.classList.add('show');
            confirmMsg.classList.add('show');

            if (password.value.length === 0) {
                confirmMsg.textContent = 'Digite a senha primeiro.';
            } else if (!passwordIsValid) {
                confirmWrap.classList.add('error');
                connector.classList.add('error');
                confirmMsg.classList.add('error');
                confirmMsg.textContent = 'A senha ainda precisa cumprir todos os requisitos.';
            } else if (passwordsMatch) {
                confirmWrap.classList.add('valid');
                connector.classList.add('valid');
                confirmMsg.classList.add('valid');
                confirmMsg.textContent = 'Senhas conectadas com sucesso.';
            } else {
                confirmWrap.classList.add('error');
                connector.classList.add('error');
                confirmMsg.classList.add('error');
                confirmMsg.textContent = 'Falta só ajustar: as senhas ainda não coincidem.';
            }
        }

        const requiredFieldsFilled = nameField.value.trim() !== '' && usernameField.value.trim() !== '';
        const canSubmit = requiredFieldsFilled && passwordIsValid && passwordsMatch;

        submitBtn.classList.toggle('enabled', canSubmit);
        submitBtn.disabled = !canSubmit;
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

    function showFormMessage(message) {
        formValidationMessage.textContent = message;
        formValidationMessage.classList.add('show', 'error');
    }

    function hideFormMessage() {
        formValidationMessage.textContent = '';
        formValidationMessage.classList.remove('show', 'error');
    }

    password.addEventListener('focus', () => {
        passwordBubble.classList.add('show');
    });
    password.addEventListener('input', () => {
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

    normalFields.forEach(field => {
        field.addEventListener('focus', () => {
            passwordBubble.classList.remove('show');
        });
        field.addEventListener('input', updateConfirmUI);
    });

    submitBtn.addEventListener('focus', () => {
        passwordBubble.classList.remove('show');
    });

    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('mousedown', event => {
            event.preventDefault();
        });

        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.target);
            const hidden = target.type === 'password';

            target.type = hidden ? 'text' : 'password';
            button.textContent = hidden ? 'Ocultar' : 'Mostrar';
            button.setAttribute('aria-label', hidden ? 'Ocultar senha' : 'Mostrar senha');
        });
    });

    form.addEventListener('submit', event => {
        hideFormMessage();

        if (nameField.value.trim() === '' || usernameField.value.trim() === '') {
            event.preventDefault();
            showFormMessage('Informe nome e usuário para criar a conta.');
            return;
        }

        if (!isPasswordValid()) {
            event.preventDefault();
            passwordBubble.classList.add('show');
            showFormMessage('A senha ainda não atende a todos os requisitos.');
            return;
        }

        if (password.value !== confirmPassword.value) {
            event.preventDefault();
            showFormMessage('As senhas precisam ser iguais antes de enviar.');
        }
    });

    updatePasswordUI();
})();
