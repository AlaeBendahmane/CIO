<div class="modal fade" id="modalPasswordReset" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changement du mot de passe obligatoire</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" id="new_pw" class="form-control" placeholder="******">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" id="confirm_pw" class="form-control" placeholder="******">
                </div>

                <div id="password-requirements" class="mt-2 p-3 border rounded bg-light" style="font-size: 0.85rem; display:none;">
                    <div id="dynamic-list"></div>
                    <div id="req-match" class="text-danger">✖ Confirmation identique</div>
                </div>

                <!-- <div id="erreurPW" class="mb-3" style="display: flex;justify-content: center;align-items: center;color:red;"></div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary px-5 shadow-sm" id="btnChange" onclick="submitPasswordChange_()" disabled>
                    Mettre à jour
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let activeRules = [];
    let passwordPattern = "";

    async function initPasswordResetConfig() {
        try {
            const formData = new FormData();
            formData.append('key', 'PasswordRegex');
            const response = await fetch('../api/getParams.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success && data.message) {
                let cleanRegex = data.message.replace(/^\/|\/$/g, '');
                passwordPattern = new RegExp(cleanRegex);
                buildUI(cleanRegex);
            }
        } catch (e) {
            console.error("Erreur config:", e);
        }
    }

    function buildUI(cleanRegex) {
        const listContainer = document.getElementById('dynamic-list');
        listContainer.innerHTML = "";
        activeRules = [];

        const ruleMap = [{
                regex: /[A-Z]/,
                id: 'req-upper',
                label: 'Une majuscule (A-Z)'
            },
            {
                regex: /[a-z]/,
                id: 'req-lower',
                label: 'Une minuscule (a-z)'
            },
            {
                regex: /\d/,
                id: 'req-number',
                label: 'Un chiffre (0-9)'
            },
            {
                regex: /[@$!%*?&]/,
                id: 'req-special',
                label: 'Un caractère spécial (@$!%*?&)'
            }
        ];

        // Extraction dynamique de la longueur
        let lengthMatch = cleanRegex.match(/\{(\d+),/);
        let minLength = lengthMatch ? lengthMatch[1] : 1;

        activeRules.push({
            id: 'req-length',
            label: `Au moins ${minLength} caractères`,
            test: (v) => v.length >= minLength
        });

        ruleMap.forEach(rule => {
            if (cleanRegex.includes(rule.regex.source)) {
                activeRules.push({
                    id: rule.id,
                    label: rule.label,
                    test: (v) => rule.regex.test(v)
                });
            }
        });

        activeRules.forEach(rule => {
            listContainer.innerHTML += `<div id="${rule.id}" class="text-danger">✖ ${rule.label}</div>`;
        });
        document.getElementById('password-requirements').style.display = 'block';
    }

    // Validation en temps réel
    function validateFinal() {
        const pw = document.getElementById('new_pw').value;
        const confirm = document.getElementById('confirm_pw').value;

        // 1. Update visuel des règles Regex
        activeRules.forEach(rule => {
            const el = document.getElementById(rule.id);
            const isValid = rule.test(pw);
            el.className = isValid ? 'text-success fw-bold' : 'text-danger';
            el.innerHTML = (isValid ? '✔ ' : '✖ ') + rule.label;
        });

        // 2. Update visuel de la correspondance
        const isMatch = (pw === confirm && pw !== "");
        const matchEl = document.getElementById('req-match');
        matchEl.className = isMatch ? 'text-success fw-bold' : 'text-danger';
        matchEl.innerHTML = (isMatch ? '✔ ' : '✖ ') + "Confirmation identique";

        // 3. Activation du bouton
        const isValidGlobal = passwordPattern.test(pw);
        document.getElementById("btnChange").disabled = !(isValidGlobal && isMatch);
    }

    // Event Listeners
    document.getElementById('new_pw').addEventListener('input', validateFinal);
    document.getElementById('confirm_pw').addEventListener('input', validateFinal);

    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalPasswordReset'), {
            backdrop: 'static',
            keyboard: false
        });
        myModal.show();
        initPasswordResetConfig(); // Lance le chargement du Regex

    });
</script>

<script>
    async function submitPasswordChange_() {
        const newPw = document.getElementById('new_pw').value;
        const confirmPw = document.getElementById('confirm_pw').value;
        if (!newPw || !confirmPw || !newPw) {
            document.getElementById('erreurPW').innerHTML = "Données incomplètes !";
            return;
        }

        if (newPw !== confirmPw) {
            document.getElementById('erreurPW').innerHTML = "Les nouveaux mots de passe ne correspondent pas !";
            return;
        }

        const response = await fetch('../api/profile_updates.php?action=change_password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'from': 'required',
                newPw
            })
        });

        const result = await response.json();
        if (result.success) {
            // Close modal using Bootstrap 5 syntax
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalPasswordReset'));
            modal.hide();
        } else {
            document.getElementById('erreurPW').innerHTML = result.message;
        }
    }
</script>