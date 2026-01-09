document.addEventListener('DOMContentLoaded', function(){
    const senha1 = document.getElementById('senha1');
    const strengthBar = document.getElementById('senhaStrength');
    const strengthText = document.getElementById('senhaStrengthText');
    if(!senha1 || !strengthBar || !strengthText) return;

    function scorePassword(p){
        let score = 0;
        if (!p) return score;
        if (p.length >= 8) score += 25;
        if (p.match(/[a-z]/)) score += 15;
        if (p.match(/[A-Z]/)) score += 20;
        if (p.match(/[0-9]/)) score += 20;
        if (p.match(/[^A-Za-z0-9]/)) score += 20;
        return Math.min(score,100);
    }

    function updateStrength(){
        const val = senha1.value;
        const sc = scorePassword(val);
        strengthBar.style.width = sc + '%';
        strengthBar.setAttribute('aria-valuenow', sc);
        let text = 'Fraca';
        let cls = 'bg-danger';
        if (sc >= 75){ text = 'Forte'; cls = 'bg-success'; }
        else if (sc >= 45){ text = 'Média'; cls = 'bg-warning'; }
        strengthBar.className = 'progress-bar ' + cls;
        strengthText.textContent = 'Força da senha: ' + text;
    }

    senha1.addEventListener('input', updateStrength);
});
