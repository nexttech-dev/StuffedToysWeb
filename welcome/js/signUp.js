const signUpForm = document.querySelector(".signupForm form"),
  signUpBtn = signUpForm.querySelector(".signUpBtn input"),
  errorText = signUpForm.querySelector(".error-text"),
  fullName = signUpForm.querySelector('[name="fullName"]'),
  userName = signUpForm.querySelector('[name="userName"]'),
  email = signUpForm.querySelector('[name="email"]'),
  pwd = signUpForm.querySelector('[name="pwd"]'),
  confirmPwd = signUpForm.querySelector('[name="confirmPwd"]');

signUpForm.onsubmit = (e) => {
  e.preventDefault();
};

signUpBtn.onclick = () => {
  if (
    fullName.value.length == 0 ||
    userName.value.length == 0 ||
    email.value.length == 0 ||
    pwd.value.length == 0 ||
    confirmPwd.value.length == 0
  ) {
    errorText.style.display = "block";
    errorText.textContent = "All Fields Are required!";
  } else {
    if (pwd.value == confirmPwd.value) {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "php/signUp.php", true);
      xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            let data = xhr.response;
            if (data === "success") {
              location.href = "users.php";
            } else {
              errorText.style.display = "block";
              errorText.textContent = data;
            }
          }
        }
      };
      let formData = new FormData(signUpForm);
      xhr.send(formData);
    } else {
      errorText.style.display = "block";
      errorText.textContent = "Password did not matched.";
    }
  }
};
