

document.addEventListener('DOMContentLoaded', () => {
  const forms = document.querySelectorAll('form[data-validate="true"]');

  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      let isValid = true;
      const requiredInputs = this.querySelectorAll('[required]');

      requiredInputs.forEach(input => {
        const errorMsg = input.parentElement.querySelector('.form-error');
        if (errorMsg) errorMsg.remove();

        if (!input.value.trim()) {
          isValid = false;
          highlightError(input, 'This field is required.');
        } else if (input.type === 'email' && !validateEmail(input.value.trim())) {
          isValid = false;
          highlightError(input, 'Please enter a valid email address.');
        } else if (input.type === 'tel' && input.value.trim().length < 8) {
          isValid = false;
          highlightError(input, 'Please enter a valid phone number.');
        } else {
          clearHighlight(input);
        }
      });

      const password = this.querySelector('input[name="password"]');
      const confirmPassword = this.querySelector('input[name="password_confirm"]');
      if (password && confirmPassword && password.value !== confirmPassword.value) {
        isValid = false;
        highlightError(confirmPassword, 'Passwords do not match.');
      }

      if (!isValid) {
        e.preventDefault();
        showToast('error', 'Please correct the errors in the form before submitting.');
      }
    });
  });

  function highlightError(input, msg) {
    input.style.borderColor = '#ef4444';
    const err = document.createElement('span');
    err.className = 'form-error';
    err.textContent = msg;
    input.parentElement.appendChild(err);
  }

  function clearHighlight(input) {
    input.style.borderColor = '';
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }
});
