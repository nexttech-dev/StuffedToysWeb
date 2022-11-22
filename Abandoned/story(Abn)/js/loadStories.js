const chatInbox = document.querySelector(".chat-box");
const queryString = window.location.search;
const storyName = document.querySelector(".details span");
const urlParams = new URLSearchParams(queryString);
const storyId = urlParams.get("storyId");
let xhr = new XMLHttpRequest();
xhr.open("POST", "./php/loadStories.php", true);
xhr.onload = () => {
  if (xhr.readyState === XMLHttpRequest.DONE) {
    if (xhr.status === 200) {
      let data = xhr.response;
      var json = JSON.parse(xhr.responseText);
      console.log("JSON DATA", json);
      if (json.result && !json.error) {
        chatInbox.innerHTML = json.msg;
        storyName.innerHTML = json.storyName;
      } else if (!json.result && json.error) {
        chatInbox.innerHTML = json.msg;
        storyName.innerHTML = json.storyName;
        console.log("No messages Found!");
      } else {
        console.log("Fuck Off!");
      }
    } else {
      console.log("Error");
    }
  }
};
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.send("storyId=" + storyId + "&char=2");
