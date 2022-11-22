function renameSheet(chap, newName) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/renameSheet.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        if (json.success) {
          $(".renameSheetPopUp").removeClass("model-open");
          console.log(
            "Name of sheet",
            document.querySelector(".sheet_" + chap)
          );
          document.querySelector(".sheet_" + chap).innerHTML = newName;

          document.querySelector(".title-bar").innerHTML =
            storyTitle.substring(0, storyTitle.indexOf("-")) + " - " + newName;

          // $(element).blur();
          // console.log("Char Name updated Succesfully!", json);
        } else {
          console.log("Char Not Updated!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + storyId + "&chap=" + chap + "&newName=" + newName);
}
function clearSheet(chap) {
  document.querySelector(".confirmClearSheet").disabled = true;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/clearSheet.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        if (json.success) {
          $(".clearSheetPopUp").removeClass("model-open");
          location.reload();
        } else {
          console.log("Char Not Updated!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + storyId + "&chap=" + chap);
}
function duplicateSheet(chap) {
  document.querySelector(".confirmClearSheet").disabled = true;
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/duplicateSheet.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        if (json.success) {
          $(".clearSheetPopUp").removeClass("model-open");
          location.reload();
        } else {
          console.log("Char Not Updated!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + storyId + "&chap=" + chap);
}
function deleteSheet(chap) {
  document.querySelector(".confirmClearSheet").disabled = true;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/deleteSheet.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        if (json.success) {
          $(".clearSheetPopUp").removeClass("model-open");
          let currentUrl =
            location.protocol + "//" + location.host + location.pathname;
          window.location.href =
            currentUrl +
            "?storyId=" +
            storyId +
            "&chapter=" +
            (parseInt(chap) - 1);
          // location.reload();
        } else {
          console.log("Char Not Updated!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + storyId + "&chap=" + chap);
}
