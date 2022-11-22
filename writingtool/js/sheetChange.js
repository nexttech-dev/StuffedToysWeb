function sheetChange(ele, sheetNumber) {
  console.log("Sheet Number", sheetNumber);
  let currentUrl = location.protocol + "//" + location.host + location.pathname;
  window.location.href =
    currentUrl + "?storyId=" + storyId + "&chapter=" + sheetNumber;
  //   console.log(location.protocol + "//" + location.host + location.pathname);
}
function newSheet(ele) {
  let xhrNewSheet = new XMLHttpRequest();
  xhrNewSheet.open("POST", "./php/newSheet.php", true);
  xhrNewSheet.onload = () => {
    if (xhrNewSheet.readyState === XMLHttpRequest.DONE) {
      if (xhrNewSheet.status === 200) {
        let data = xhrNewSheet.response;
        var jsonNewSheet = JSON.parse(xhrNewSheet.responseText);
        if (jsonNewSheet.result) {
          console.log("JSON", jsonNewSheet);
          console.log("Aux Added Successfully!");
          let currentUrl =
            location.protocol + "//" + location.host + location.pathname;
          window.location.href =
            currentUrl +
            "?storyId=" +
            storyId +
            "&chapter=" +
            jsonNewSheet.data;
          //   location.reload();
        } else {
          console.log("Aux not added!", jsonNewSheet);
        }
        console.log(jsonNewSheet);
      }
    }
  };
  xhrNewSheet.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  xhrNewSheet.send("storyId=" + storyId);
}
