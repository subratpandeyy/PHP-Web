document.querySelectorAll(".read-more-btn").forEach((button) => {
    button.addEventListener("click", (e) => {
      const post = e.target.closest(".post");
      const content = post.querySelector("p");
  
      if (content.style.whiteSpace === "nowrap") {
        content.style.whiteSpace = "normal"; // Expand content
        content.style.overflow = "visible"; // Remove overflow
      } else {
        content.style.whiteSpace = "nowrap"; // Collapse content
        content.style.overflow = "hidden"; // Restore overflow
      }
    });
  });