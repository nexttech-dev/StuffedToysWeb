function signOut() {
  console.log("Pressed!");
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/signOut/signOut.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        // searchResult.innerHTML = data;
        console.log("data", data);
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send();
}
