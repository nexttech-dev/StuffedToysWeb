function replaceAction(ele) {
  const replaceBtnClasses = ele.className.split(/\s+/);
  ele.classList.remove(
    replaceBtnClasses[1],
    replaceBtnClasses[2],
    replaceBtnClasses[3],
    replaceBtnClasses[4]
  );
  console.log(replaceBtnClasses);
  if (replaceBtnClasses[4] == "sheetCtrl") {
    var msg = document.querySelector(".cell-row .selected").innerHTML;
  } else {
    var msg = document.querySelector(".popUpMsg").value;
  }
  let xhrIv = new XMLHttpRequest();
  xhrIv.open("POST", "./php/sendMsgIv.php", true);
  xhrIv.onload = () => {
    if (xhrIv.readyState === XMLHttpRequest.DONE) {
      if (xhrIv.status === 200) {
        var jsonIv = JSON.parse(xhrIv.responseText);
        console.log("JSON DIR", jsonIv);
        if (jsonIv.result) {
          console.log("Message Updated Successfully");
        }
        $(".error").removeClass("model-open");
      }
    }
  };
  xhrIv.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrIv.send(
    "sendAs=" +
      replaceBtnClasses[1] +
      "&msg=" +
      msg +
      "&storyId=" +
      storyId +
      "&charCode=" +
      replaceBtnClasses[3].split("_")[1] +
      "&msgId=" +
      replaceBtnClasses[2].split("_")[1] +
      "&chapter=" +
      chapter
  );
}
