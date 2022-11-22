function addChar(category) {
  var newCharName = document.querySelector(".charName");
  if (newCharName.value) {
    let xhrAddChar = new XMLHttpRequest();
    xhrAddChar.open("POST", "./php/addChar.php", true);
    xhrAddChar.onload = () => {
      if (xhrAddChar.readyState === XMLHttpRequest.DONE) {
        if (xhrAddChar.status === 200) {
          let data = xhrAddChar.response;
          // console.log(data);
          var jsonAddChar = JSON.parse(xhrAddChar.responseText);
          if (jsonAddChar.success) {
            console.log("JSON", jsonAddChar);
            console.log("Aux Added Successfully!");
            location.reload();
          } else {
            console.log("Aux not added!", jsonAddChar);
          }
          console.log(jsonAddChar);
        }
      }
    };
    xhrAddChar.setRequestHeader(
      "Content-type",
      "application/x-www-form-urlencoded"
    );
    xhrAddChar.send(
      "newCharName=" +
        newCharName.value +
        "&storyId=" +
        storyId +
        "&category=" +
        category +
        "&chapter=" +
        chapter
    );
  } else {
    console.log("Please enter char name!");
  }
}
