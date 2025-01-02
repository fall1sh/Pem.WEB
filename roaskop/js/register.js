function validateForm() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
  
    if (password !== confirmPassword) {
      alert("Password dan Ulangi Password tidak cocok!");
      return false;
    }
    return true;
  }
  