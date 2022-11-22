const signInForm = document.querySelector(".signinForm form"),
  signInBtn = signInForm.querySelector(".signInBtn input"),
  errorTextSignIn = signInForm.querySelector(".error-text"),
  emailSignIn = signInForm.querySelector("[name='email']"),
  pwdSignIn = signInForm.querySelector("[name='pwd']");

signInForm.onsubmit = (e) => {
  e.preventDefault();
};

signInBtn.onclick = () => {
  console.log("email", emailSignIn.value.length);
  if (emailSignIn.value.length != 0 && pwdSignIn.value.length != 0) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/signIn.php", true);
    xhr.onload = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          let data = xhr.response;
          if (data === "success") {
            location.href = "../index.php";
          } else {
            errorTextSignIn.style.display = "block";
            errorTextSignIn.textContent = data;
          }
        }
      }
    };
    let formData = new FormData(signInForm);
    xhr.send(formData);
  } else {
    errorTextSignIn.style.display = "block";
    errorTextSignIn.textContent = "All fields are required!";
  }
};
