const searchBar = document.querySelector(".input input"),
  searchResult = document.querySelector(".searchResult");

searchBar.onkeyup = () => {
  let searchTerm = searchBar.value;
  if (searchTerm != "") {
    console.log("Search Ite", searchTerm);

    searchBar.classList.add("active");
  } else {
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/search.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        searchResult.innerHTML = data;
        console.log("data", data);
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
};

// setInterval(() => {
//   let xhr = new XMLHttpRequest();
//   xhr.open("GET", "php/search.php", true);
//   xhr.onload = () => {
//     if (xhr.readyState === XMLHttpRequest.DONE) {
//       if (xhr.status === 200) {
//         let data = xhr.response;
//         if (!searchBar.classList.contains("active")) {
//           searchResult.innerHTML = data;
//         }
//       }
//     }
//   };
//   xhr.send();
// }, 500);
