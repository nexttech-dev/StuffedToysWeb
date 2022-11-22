const auxCharsShow = document.querySelector(".auxCharsShow");

let xhrForAuxChars = new XMLHttpRequest();
xhrForAuxChars.open("POST", "./php/fetchingAllAuxChars.php", true);
xhrForAuxChars.onload = () => {
  if (xhrForAuxChars.readyState === XMLHttpRequest.DONE) {
    if (xhrForAuxChars.status === 200) {
      let data = xhrForAuxChars.response;
      var json = JSON.parse(xhrForAuxChars.responseText);
      if (json.success) {
        // console.log(json.data);
        let output =
          '<button class="auxButtonPrompt" onclick="auxMsgSend(this)" id="' +
          json.data[1].charCode +
          '" style="background-color:' +
          json.data[1].charColor +
          '">' +
          json.data[1].charName +
          "</button>";
        for (let i = 2; i <= json.data.totalAuxChars; i++) {
          //   console.log();
          output =
            output +
            '<button class="auxButtonPrompt" onclick="auxMsgSend(this)" id="' +
            json.data[i].charCode +
            '" style="background-color:' +
            json.data[i].charColor +
            '">' +
            json.data[i].charName +
            "</button>";
        }
        auxCharsShow.innerHTML = output;
        console.log(output);
      } else {
        console.log("Fuck Off!");
      }
    } else {
      console.log("Error");
    }
  }
};
xhrForAuxChars.setRequestHeader(
  "Content-type",
  "application/x-www-form-urlencoded"
);
xhrForAuxChars.send("storyId=" + storyId);
