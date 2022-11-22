const signinBtn = document.querySelector(".signinBtn");
const signupBtn = document.querySelector(".signupBtn");
const body = document.querySelector("body");
const formBx = document.querySelector(".formBx");

signupBtn.onclick = function () {
  formBx.classList.add("active");
  body.classList.add("active");
};
signinBtn.onclick = function () {
  formBx.classList.remove("active");
  body.classList.remove("active");
};
