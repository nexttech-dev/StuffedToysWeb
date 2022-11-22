function save(nameFlag, fullStory, saveCat) {
  var saveName = "null";
  var range = null;

  if (nameFlag) {
    saveName = document.querySelector(".nameOfSave").value;
  }
  if (fullStory) {
    range = "full";
  } else {
    range = "sheet";
  }

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/saveSheet.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        // var json = JSON.parse(xhr.responseText);
        // if (json.success) {
        // } else {
        // }
        $(".saveAsPopUp").removeClass("model-open");

        console.log("Save Report", data);
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(
    "storyId=" +
      storyId +
      "&chap=" +
      chapter +
      "&saveCat=" +
      saveCat +
      "&saveRange=" +
      range +
      "&saveName=" +
      saveName
  );
}

function loadOldVersions(params) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/getOlderVersions.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        var allSaves = JSON.parse(json.saves);

        var savesNum = Object.keys(allSaves);
        var savesHtml = "";
        var savesFullHtml = "";

        if (savesNum.length >> 0) {
          savesNum.forEach(function (i, index) {
            if (allSaves[i]["range"] == "full") {
              savesFullHtml +=
                "<div class='prevSaves'><h class='saveName'>" +
                allSaves[i]["name"] +
                "</h><p class='sheetName'> Complete '" +
                allSaves[i]["storyName"] +
                "' is saved!</p><p class='saveDateTime'> " +
                allSaves[i]["date"] +
                "</p><button class='loadSaveBtn' onclick='restoreOlderVersions(" +
                allSaves[i]["id"] +
                ")'>Load</button></div>";
            } else {
              savesHtml +=
                "<div class='prevSaves'><h class='saveName'>" +
                allSaves[i]["name"] +
                "</h><p class='sheetName'> " +
                allSaves[i]["details"][1]["sheet"] +
                "</p><p class='saveDateTime'> " +
                allSaves[i]["date"] +
                "</p><button class='loadSaveBtn'  onclick='restoreOlderVersions(" +
                allSaves[i]["id"] +
                ")'>Load</button></div>";
            }
          });
          if (savesHtml.length >> 0) {
            document.querySelector(".chaptersOlderVersions").innerHTML =
              savesHtml;
          } else {
            document.querySelector(".chaptersOlderVersions").innerHTML =
              "<p class='noSaves'>No Saves Yet!</p>";
          }
          if (savesFullHtml.length >> 0) {
            document.querySelector(".storyOlderVersions").innerHTML =
              savesFullHtml;
          } else {
            document.querySelector(".storyOlderVersions").innerHTML =
              "<p class='noSaves'>No Saves Yet!</p>";
          }
        } else {
          document.querySelector(".chaptersOlderVersions").innerHTML =
            "<p class='noSaves'>No Saves Yet!</p>";
          document.querySelector(".storyOlderVersions").innerHTML =
            "<p class='noSaves'>No Saves Yet!</p>";
        }

        // if (json.success) {
        // } else {
        // }
        console.log("Save Report", JSON.parse(json.saves));
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + storyId + "&chap=" + chapter);
}

function restoreOlderVersions(saveId) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/loadOlderVersion.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        // if (json.success) {
        // } else {
        // }
        // $(".saveAsPopUp").removeClass("model-open");

        let currentUrl =
          location.protocol + "//" + location.host + location.pathname;
        window.location.href =
          currentUrl + "?storyId=" + storyId + "&chapter=1";
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + storyId + "&chap=" + chapter + "&saveId=" + saveId);
}
