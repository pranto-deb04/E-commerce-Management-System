function togglePasswordVisibility(fieldId, toggleElement) {
  const passwordField = document.getElementById(fieldId);

  if (passwordField.type === "password") {
    passwordField.type = "text";
    toggleElement.textContent = "🙈";
  } else {
    passwordField.type = "password";
    toggleElement.textContent = "👁️";
  }
}

const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm-password');
const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');
const matchError = document.getElementById('matchError');
const registerForm = document.getElementById('registerForm');

passwordInput.addEventListener('input', function () {
  const value = passwordInput.value;
  let score = 0;

  if (value.length >= 8) score++;
  if (/[A-Z]/.test(value)) score++;
  if (/[0-9]/.test(value)) score++;
  if (/[^A-Za-z0-9]/.test(value)) score++;

  if (value.length === 0) {
    strengthBar.style.width = '0%';
    strengthBar.style.backgroundColor = '#ef4444';
    strengthText.textContent = 'Strength: Empty';
    strengthText.style.color = '#94a3b8';
  } else if (score <= 1) {
    strengthBar.style.width = '25%';
    strengthBar.style.backgroundColor = '#ef4444';
    strengthText.textContent = 'Strength: Weak';
    strengthText.style.color = '#f87171';
  } else if (score <= 3) {
    strengthBar.style.width = '60%';
    strengthBar.style.backgroundColor = '#f59e0b';
    strengthText.textContent = 'Strength: Moderate';
    strengthText.style.color = '#fbbf24';
  } else {
    strengthBar.style.width = '100%';
    strengthBar.style.backgroundColor = '#10b981';
    strengthText.textContent = 'Strength: Strong';
    strengthText.style.color = '#34d399';
  }

  checkPasswordMatch();
});

confirmPasswordInput.addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
  if (confirmPasswordInput.value === '') {
    matchError.style.display = 'none';
    return false;
  }

  if (passwordInput.value === confirmPasswordInput.value) {
    matchError.style.display = 'none';
    return true;
  } else {
    matchError.style.display = 'block';
    return false;
  }
}

registerForm.addEventListener('submit', function (e) {
  if (!checkPasswordMatch() || passwordInput.value.length < 8) {
    e.preventDefault();
    alert('Please ensure passwords match and meet required standards.');
  }
});