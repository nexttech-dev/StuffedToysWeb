function updateCharName(event, element, charCodeForUpdation) {
  //   const charName = $(element).attr("id");

  // if (event.keyCode == 13) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/updateCharName.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        // var json = JSON.parse(xhr.responseText);
        console.log("DATA", data);
        // if (json.success) {
        //   // $(element).blur();
        //   // console.log("Char Name updated Succesfully!", json);
        // } else {
        //   console.log("Char Not Updated!");
        // }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(
    "charCode=" +
      charCodeForUpdation +
      "&storyId=" +
      storyId +
      "&charNewName=" +
      element.value
  );
  // }
}
