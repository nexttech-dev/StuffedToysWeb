var box = document.getElementById("box");
var prompt = document.querySelector(".sendBtnSelPrompt");
var down = false;

function toggleNotifi() {
  if (down) {
    box.style.height = "0px";
    box.style.opacity = 0;
    down = false;
  } else {
    box.style.height = "510px";
    box.style.opacity = 1;
    down = true;
  }
}
function createStory() {
  var nameOfStory = document.querySelector(".storyNameInput");
  nameOfStory = nameOfStory.value;
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/createStory.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status == 200) {
        var json = JSON.parse(xhr.responseText);
        if (json.result) {
          $(".custom-model-main").removeClass("model-open");
          openUpTheStory(json.data);
          // loadStories()
        } else {
          console.log("Something went wrong!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyName=" + nameOfStory);
}

function popUp() {
  $(document).ready(function () {
    $(".sendBtnSelPrompt").addClass("model-open");
  });
}

function openUpTheStory(storyId) {
  console.log("Story Id", storyId);
  window.location = "./writingtool/index.php?storyId=" + storyId + "&chapter=1";
}
