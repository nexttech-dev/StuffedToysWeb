const content = document.querySelector(".content"),
  usersList = document.querySelector(".users-list");

let xhr = new XMLHttpRequest();
xhr.open("POST", "./php/stories.php", true);
xhr.onload = () => {
  if (xhr.readyState === XMLHttpRequest.DONE) {
    if (xhr.status === 200) {
      let data = xhr.response;
      var json = JSON.parse(xhr.responseText);
      console.log(json);
      content.innerHTML = json.profile;
      usersList.innerHTML = json.stories;
    }
  }
};
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.send();
