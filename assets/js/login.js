function togglePassword() {
    const pass = document.getElementById("password");
    const icon = document.querySelector(".toggle-password i");
    if (pass.type === "password") {
      pass.type = "text";
      icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
      pass.type = "password";
      icon.classList.replace("fa-eye-slash", "fa-eye");
    }
  }
  
  function showPopup(message) {
    const popup = document.getElementById("popup");
    popup.textContent = message;
    popup.style.display = "block";
    setTimeout(() => popup.style.display = "none", 3000);
  }
  
  function validateForm() {
    const user = document.getElementById("username").value.trim();
    const pass = document.getElementById("password").value.trim();
    if (!user || !pass) {
      showPopup("Please fill in all fields.");
      return false;
    }
    return true;
  }
  
  // Show server-side error if exists
  if (loginError) {
    window.onload = () => { showPopup(loginError); };
  }
  