const storiesList = document.querySelector(".storiesList"),
  storiesTable = document.querySelector(".storiesTable"),
  details = document.querySelector(".recentOrders");
console.log(storiesList, details);
function loadStories() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/stories/stories.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        // var json = JSON.parse(xhr.responseText);
        var json = (function(raw) {
          try {
            return JSON.parse(raw);
          } catch (err) {
            return false;
          }
        })(xhr.responseText);
        console.log(json);
        details.style.display = "none";
        storiesList.style.display = "contents";
        //   content.innerHTML = json.profile;
        if(json){
          storiesTable.innerHTML = json.stories;
        }

        $(document).ready(function () {
          $(".custom-model-main").removeClass("model-open");
        });
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send();
}

function loadUsers() {
  details.style.display = "contents";
  storiesList.style.display = "none";
}

function continueStory(data) {
  console.log("Data", data.id);
  window.location = "./writingtool/index.php?storyId=" + data.id + "&chapter=1";
}
function deleteStory(data) {
  console.log("Data", data.id);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/stories/deleteStory.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        console.log(json);
        location.reload();
        // details.style.display = "none";
        // storiesList.style.display = "contents";
        // //   content.innerHTML = json.profile;
        // storiesTable.innerHTML = json.stories;
        // $(document).ready(function () {
        //   $(".custom-model-main").removeClass("model-open");
        // });
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + data.id);
}
