const searchBar = document.querySelector(".searchInput"),
  searchResult = document.querySelector(".storyTable");
searchBar.onkeyup = () => {
  let searchTerm = searchBar.value;
  if (searchTerm != "") {
    searchBar.classList.add("active");
  } else {
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/search/search.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        searchResult.innerHTML = data;
        // console.log("data", data);
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
};

let xhrLoadUsers = new XMLHttpRequest();
xhrLoadUsers.open("POST", "./php/search/search.php", true);
xhrLoadUsers.onload = () => {
  if (xhrLoadUsers.readyState === XMLHttpRequest.DONE) {
    if (xhrLoadUsers.status === 200) {
      let data = xhrLoadUsers.response;
      searchResult.innerHTML = data;
    }
  }
};
xhrLoadUsers.setRequestHeader(
  "Content-type",
  "application/x-www-form-urlencoded"
);
xhrLoadUsers.send("searchTerm=" + "");
